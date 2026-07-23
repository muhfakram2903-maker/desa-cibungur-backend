<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePendudukRequest;
use App\Http\Requests\Admin\UpdatePendudukRequest;
use App\Services\PendudukService;
use App\Models\Dusun;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PendudukController extends Controller
{
    public function __construct(
        private readonly PendudukService $service
    ) {
        $this->middleware('permission:penduduk.view')->only(['index', 'show']);
        $this->middleware('permission:penduduk.create')->only(['create', 'store']);
        $this->middleware('permission:penduduk.edit')->only(['edit', 'update']);
        $this->middleware('permission:penduduk.delete')->only('destroy');
        $this->middleware('permission:penduduk.import')->only('import');
        $this->middleware('permission:penduduk.export')->only(['exportExcel', 'exportPdf']);
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'dusun_id', 'rw_id', 'rt_id', 'jenis_kelamin', 'agama_id', 'pendidikan_id', 'status_aktif']);
        $penduduk = $this->service->getAll($filters, 20);
        $statistik = $this->service->getStatistik();
        $dusunList = Dusun::active()->get();

        return view('admin.penduduk.index', compact('penduduk', 'statistik', 'filters', 'dusunList'));
    }

    public function create(): View
    {
        return view('admin.penduduk.create', $this->getFormData());
    }

    public function store(StorePendudukRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil ditambahkan.');
    }

    public function show(int $id): View
    {
        $penduduk = $this->service->getAll([], 1); // dummy, akan diganti
        $penduduk = \App\Models\Penduduk::with([
            'dusun', 'rw', 'rt', 'agama', 'pendidikan', 'pekerjaan', 'riwayat.user'
        ])->findOrFail($id);

        return view('admin.penduduk.show', compact('penduduk'));
    }

    public function edit(int $id): View
    {
        $penduduk = \App\Models\Penduduk::findOrFail($id);

        return view('admin.penduduk.edit', array_merge(
            $this->getFormData(),
            compact('penduduk')
        ));
    }

    public function update(UpdatePendudukRequest $request, int $id): RedirectResponse
    {
        $this->service->update($id, $request->validated());

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->delete($id);

        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Data penduduk berhasil dihapus.');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        // Import akan dihandle via Job + Excel
        // TODO: Implement ImportPendudukJob
        return redirect()->route('admin.penduduk.index')
            ->with('success', 'Import sedang diproses. Hasilnya akan dikirim via notifikasi.');
    }

    public function exportExcel(Request $request)
    {
        // TODO: Implement dengan Maatwebsite Excel
        return response()->download('/tmp/penduduk.xlsx');
    }

    public function exportPdf(Request $request)
    {
        // TODO: Implement dengan DomPDF
        return response()->download('/tmp/penduduk.pdf');
    }

    private function getFormData(): array
    {
        return [
            'dusunList'     => Dusun::active()->get(),
            'rwList'        => Rw::active()->with('dusun')->get(),
            'rtList'        => Rt::active()->with('rw')->get(),
            'agamaList'     => Agama::all(),
            'pendidikanList'=> Pendidikan::ordered()->get(),
            'pekerjaanList' => Pekerjaan::all(),
        ];
    }
}
