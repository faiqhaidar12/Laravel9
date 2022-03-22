<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Halo <b>{{ Auth::user()->name }} </b>

            <?php
            //ubah timezone menjadi jakarta
            date_default_timezone_set('Asia/Jakarta');
            
            //ambil jam dan menit
            $jam = date('H:i');
            
            //atur salam menggunakan IF
            if ($jam > '05:30' && $jam < '10:00') {
                $salam = 'Pagi';
            } elseif ($jam >= '10:00' && $jam < '15:00') {
                $salam = 'Siang';
            } elseif ($jam < '18:00') {
                $salam = 'Sore';
            } else {
                $salam = 'Malam';
            }
            //tampilkan pesan
            echo 'Selamat ' . $salam;
            ?>

            <b style="float: right">
                Total Users
                <span class="badge badge-danger">
                    {{ count($users) }}
                </span>
            </b>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">SL No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($no = 1)
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row">{{ $no++ }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
