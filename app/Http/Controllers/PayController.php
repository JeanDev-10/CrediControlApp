<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pays\StorePayRequest;
use App\Http\Requests\Pays\UpdatePayRequest;
use App\Models\Pay;
use App\Repositories\Interfaces\DebtRepositoryInterface;
use App\Services\ImageService;
use App\Services\PayService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PayController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected DebtRepositoryInterface $debtRepository, protected PayService $payService, protected ImageService $imageService) {}

    public function index(Request $request)
    {
        $pays = $this->payService->list($request->all());

        return view('pays.index', compact('pays'));
    }

    public function show(int $id)
    {
        $pay = $this->payService->get($id);
        $this->authorize('view', $pay);

        return view('pays.show', compact('pay'));
    }

    public function create(Request $request)
    {
        $debt_id = $request->query('debt_id');
        $debts = $this->debtRepository->filter(['status' => 'pendiente', 'debt_id' => $debt_id], 10000);

        return view('pays.create', compact('debts'));
    }

    public function store(StorePayRequest $request)
    {
        try {
            $this->payService->create($request->validated(), $request->file('images'));
            $redirectUrl = $request->input('redirect_to');

            return redirect($redirectUrl ?? route('pays.index'))
                ->with('success', 'Pago creada correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage())->withInput();
        }
    }

    public function edit(int $id)
    {
        $pay = $this->payService->get($id);
        $this->authorize('update', $pay);

        return view('pays.edit', compact('pay'));
    }

    public function update(UpdatePayRequest $request, Pay $pay)
    {
        try {
            $this->authorize('update', $pay);
            $pay = $this->payService->update($pay->id, $request->validated(), $request->file('images'));
            $redirectUrl = $request->input('redirect_to');
            return redirect($redirectUrl ?? route('pays.index'))
                ->with('success', 'Pago actualizado correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage())->withInput();
        }
    }

    public function destroy(Pay $pay, Request $request)
    {
        try {
            $this->authorize('delete', $pay);
            $this->payService->delete($pay->id);
            $redirectUrl = $request->input('redirect_to');
            return redirect($redirectUrl ?? route('pays.index'))
                ->with('success', 'Pago eliminado correctamente');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function destroyImage(string $id)
    {
        try {
            $image = $this->imageService->get($id);
            $this->authorize('deleteImage', $image->pay);
            $this->payService->deleteImage($id);

            return back()->with('success', 'Imagen eliminada correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function destroyAllImages(int $id)
    {
        try {
            $pay = $this->payService->get($id);
            $this->authorize('deleteImages', $pay);
            $this->payService->deleteAllImagesByPayId($id);

            return back()->with('success', 'Todas las imágenes fueron eliminadas.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors());
        } catch (Exception $e) {
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }
}
