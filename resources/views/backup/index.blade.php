@extends('layouts.app')
@section('title', 'النسخ الاحتياطية')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">📦 إدارة النسخ الاحتياطية</h4>

    <div class="mb-3 d-flex align-items-center gap-2">
        <button class="btn btn-primary" onclick="runBackup()">
            🔄 إنشاء نسخة احتياطية
        </button>
    </div>

    <div id="progress-container" class="progress mb-4" style="height: 22px; display: none;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
             role="progressbar" style="width: 0%;" id="progress-bar">0%</div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center" id="backups-table">
            <thead class="table-light">
                <tr>
                    <th>📄 الاسم</th>
                    <th>📦 الحجم</th>
                    <th>📅 التاريخ</th>
                    <th>⚙️ الإجراءات</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function runBackup() {
const url = "{{ secure_url(route('backup.create', [], false)) }}";
    const progress = document.getElementById('progress-container');
    const bar = document.getElementById('progress-bar');

    progress.style.display = 'block';
    bar.classList.remove('bg-danger');
    bar.classList.add('bg-success');
    bar.style.width = '0%';
    bar.innerText = '0%';

    let percent = 0;
    const interval = setInterval(() => {
        percent += 10;
        if (percent > 90) percent = 90;
        bar.style.width = percent + '%';
        bar.innerText = percent + '%';
    }, 400);

    fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(res => res.json())
    .then(data => {
        clearInterval(interval);
        bar.style.width = '100%';
        bar.innerText = 'تم ✅';
        loadBackups();
        setTimeout(() => progress.style.display = 'none', 1500);
    })
    .catch(err => {
        clearInterval(interval);
        bar.classList.remove('bg-success');
        bar.classList.add('bg-danger');
        bar.innerText = 'فشل النسخ ❌';
        console.error(err);
    });
}

function restoreBackup(file) {
    if (!confirm('هل أنت متأكد من استرجاع النسخة؟')) return;

    const progress = document.getElementById('progress-container');
    const bar = document.getElementById('progress-bar');

    progress.style.display = 'block';
    bar.classList.remove('bg-danger');
    bar.classList.add('bg-warning');
    bar.style.width = '0%';
    bar.innerText = 'جاري الاسترجاع...';

    let percent = 0;
    const interval = setInterval(() => {
        percent += 10;
        if (percent > 90) percent = 90;
        bar.style.width = percent + '%';
        bar.innerText = percent + '%';
    }, 500);

fetch("{{ secure_url('/backup/restore') }}/" + file)
    .then(res => res.json())
    .then(data => {
        console.log('🧪 رد السيرفر:', data); // <<< هنا
        clearInterval(interval);
        bar.classList.remove('bg-warning');
        bar.classList.add('bg-success');
        bar.style.width = '100%';
        bar.innerText = 'تم الاسترجاع ✅';
        loadBackups();
        setTimeout(() => progress.style.display = 'none', 1500);
    })
    .catch(error => {
        clearInterval(interval);
        bar.classList.remove('bg-warning');
        bar.classList.add('bg-danger');
        bar.innerText = 'فشل الاسترجاع ❌';
        console.error('Restore error:', error);
    });
}


function loadBackups() {
    fetch("{{ secure_url(route('backup.list', [], false)) }}")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector('#backups-table tbody');
            tbody.innerHTML = '';
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4">لا توجد نسخ احتياطية</td></tr>`;
            } else {
                data.forEach(file => {
                    const row = `<tr>
                        <td>${file.name}</td>
                        <td>${(file.size / 1024 / 1024).toFixed(2)} MB</td>
                        <td>${new Date(file.date * 1000).toLocaleString()}</td>
                        <td>
                            <a href="{{ url('/backup/download') }}/${file.name}" class="btn btn-sm btn-success">⬇️ تحميل</a>
                            <a href="{{ url('/backup/delete') }}/${file.name}" class="btn btn-sm btn-danger" onclick="return confirm('حذف النسخة؟')">🗑 حذف</a>
                            <button class="btn btn-sm btn-warning" onclick="restoreBackup('${file.name}')">♻️ استرجاع</button>
                        </td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            }
        });
}


document.addEventListener('DOMContentLoaded', loadBackups);
</script>
@endsection
