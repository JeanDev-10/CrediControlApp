<?php

namespace App\Services;

use App\Models\Pay;
use App\Repositories\Interfaces\DebtRepositoryInterface;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Repositories\Interfaces\PayRepositoryInterface;
use App\Services\Interfaces\ImageServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PayService
{
    public function __construct(protected PayRepositoryInterface $payRepository, protected ImageServiceInterface $imageService, protected ImageRepositoryInterface $imageRepository, protected DebtRepositoryInterface $debtRepository) {}

    public function list(array $filters = [], int $perPage = 10)
    {
        return $this->payRepository->filter($filters, $perPage);
    }

    public function get(int $id): Pay
    {
        $pay = $this->payRepository->find($id);
        $pay->load(['images', 'debt.contact']);

        return $pay;
    }

    public function create(array $data, $files = null): Pay
    {
        return DB::transaction(function () use ($data, $files) {
            $debt = $this->debtRepository->find($data['debt_id']);

            if ($debt->status === 'pagada') {
                throw ValidationException::withMessages([
                    'debt' => 'La deuda ya está pagada, no se pueden registrar más pagos.',
                ]);
            }
            // Verifica que el monto no sea mayor al saldo pendiente
            $totalPaid = $debt->pays->sum('quantity'); // Suma de todos los pagos realizados
            $remainingAmount = $debt->quantity - $totalPaid; // Saldo pendiente

            if ($data['quantity'] > $remainingAmount) {
                throw ValidationException::withMessages([
                    'quantity' => 'El monto ingresado no puede ser mayor al saldo pendiente (saldo pendiente: $' .$remainingAmount.")",
                ]);
            }
            $pay = $this->payRepository->create($data);

            if ($files) {
                $uploaded = $this->imageService->uploadImages($files, 'crediapp/pays');
                foreach ($uploaded as $img) {
                    $this->imageRepository->create([
                        'image_uuid' => $img['path'],
                        'url' => $img['url'],
                        'pay_id' => $pay->id,
                    ]);
                }
            }
            $this->recalculateDebtStatus($debt);

            return $pay->load('images');
        });
    }

    public function update(int $id, array $data, $files = null)
    {
        return DB::transaction(function () use ($id, $data, $files) {
            $pay = $this->payRepository->find($id);
            $debt = $pay->debt;

            // Verifica que el monto actualizado no supere el saldo restante
            $totalPaid = $debt->pays->sum('quantity') - $pay->quantity; // Suma de todos los pagos, excluyendo el pago actual
            $remainingAmount = $debt->quantity - $totalPaid;

            // Calcula la diferencia entre el monto actualizado y el pago original
            $amountDifference = $data['quantity'] - $pay->quantity;

            if ($amountDifference > $remainingAmount) {
                throw ValidationException::withMessages([
                    'quantity' => 'El monto actualizado no puede ser mayor al saldo pendiente (saldo pendiente : $' .$remainingAmount.")",
                ]);
            }
            $pay = $this->payRepository->update($id, $data);
            if ($files) {
                $uploaded = $this->imageService->uploadImages($files, 'crediapp/pays');
                foreach ($uploaded as $img) {
                    $this->imageRepository->create([
                        'image_uuid' => $img['path'],
                        'url' => $img['url'],
                        'pay_id' => $pay->id,
                    ]);
                }
            }
            $this->recalculateDebtStatus($pay->debt);

            return $pay->load('images');
        });
    }

    /**
     * Delete a pay and its images.
     */
    public function delete(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $pay = $this->payRepository->find($id);
            $debt = $pay->debt;

            if ($debt->status === 'pagada') {
                throw ValidationException::withMessages([
                    'debt' => 'La deuda ya está pagada, no se pueden eliminar pagos.',
                ]);
            }
            // Primero eliminamos las imágenes físicamente
            $paths = $pay->images->pluck('image_uuid')->filter()->all();
            if (! empty($paths)) {
                $this->imageService->deleteImages($paths);
            }

            // Eliminar registros de imágenes en la base de datos
            $this->imageRepository->deleteByPayId($pay->id);

            // Finalmente eliminamos el pago
            return $this->payRepository->delete($id);
        });
    }

    /**
     * Delete a single image by its uuid.
     */
    public function deleteImage(string $id): bool
    {
        return DB::transaction(function () use ($id) {
            $image = $this->imageRepository->find($id); // throws if not exists
            $pay = $image->pay;
            $debt = $pay->debt;

            // Business rule: if debt pagada, disallow deleting images
            if ($debt->status === 'pagada') {
                throw ValidationException::withMessages(['debt' => 'No se pueden eliminar imágenes de pagos de una deuda pagada.']);
            }

            // Delete from storage
            $this->imageService->deleteImage($image->image_uuid);

            // Delete DB record
            $this->imageRepository->delete($image->id);

            return true;
        });
    }

    /**
     * Delete all images for a pay.
     */
    public function deleteAllImagesByPayId(int $payId): bool
    {
        return DB::transaction(function () use ($payId) {
            $pay = $this->payRepository->find($payId);
            $debt = $pay->debt;

            if ($debt->status === 'pagada') {
                throw ValidationException::withMessages(['debt' => 'No se pueden eliminar imágenes de pagos de una deuda pagada.']);
            }

            $paths = $pay->images->pluck('image_uuid')->filter()->values()->all();

            if (! empty($paths)) {
                $this->imageService->deleteImages($paths);
            }

            $this->imageRepository->deleteByPayId($pay->id);

            return true;
        });
    }

    protected function recalculateDebtStatus($debt): void
    {
        // Reload to get up-to-date relations
        $debt->refresh();

        // Sum all pays' quantities
        // NOTE: Pay model uses 'quantity' column
        $totalPaid = $debt->pays()->sum('quantity');

        if ($totalPaid >= $debt->quantity) {
            if ($debt->status !== 'pagada') {
                $debt->update(['status' => 'pagada']);
            }
        } else {
            if ($debt->status !== 'pendiente') {
                $debt->update(['status' => 'pendiente']);
            }
        }
    }

    public function getAllWithoutPagination(array $filters = [])
    {
        return $this->payRepository->getAllWithoutPagination($filters);
    }
}
