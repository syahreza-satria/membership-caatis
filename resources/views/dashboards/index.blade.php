@extends('dashboards.layouts.main')

@section('container')
    <div class="container mt-4">
        <h2 class="fw-bold text-red">Dashboard Membership</h2>
        <hr>
        <div class="row mt-4">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100 border-red shadow">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px"
                            fill="#333333">
                            <path
                                d="M40-200v-560h80v560H40Zm120 0v-560h80v560h-80Zm120 0v-560h40v560h-40Zm120 0v-560h80v560h-80Zm120 0v-560h120v560H520Zm160 0v-560h40v560h-40Zm120 0v-560h120v560H800Z" />
                        </svg>
                        <h5 class="card-title fw-bold mb-0">Kode Verifikasi</h5>
                        <p class="card-text">Melihat kode verivikasi pesan online</p>
                        <a href="{{ route('dashboard.verification-codes') }}"
                            class="btn btn-danger background-red">Masuk</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100 border-red shadow">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px"
                            fill="#333333">
                            <path
                                d="M299-120q-91 0-155-64T80-339q0-86 57.5-148.5T281-557L120-880h280l80 160 80-160h280L680-559q85 8 142.5 70.5T880-340q0 92-64 156t-156 64q-9 0-18.5-.5T623-123q55-36 86-93.5T740-340q0-109-75.5-184.5T480-600q-109 0-184.5 75.5T220-340q0 68 28 128t88 89q-9 2-18.5 2.5t-18.5.5Zm181-40q-75 0-127.5-52.5T300-340q0-75 52.5-127.5T480-520q75 0 127.5 52.5T660-340q0 75-52.5 127.5T480-160Zm-74-70 74-56 74 56-28-91 74-53h-91l-29-96-29 96h-91l74 53-28 91Z" />
                        </svg>
                        <h5 class="card-title fw-bold mb-0">Rewards</h5>
                        <p class="card-text">Olah Rewards yang bisa ditukan</p>
                        <a href="{{ route('dashboard.rewards') }}" class="btn btn-danger background-red">Masuk</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100 border-red shadow">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px"
                            fill="#333333">
                            <path
                                d="M160-720v-80h640v80H160Zm0 560v-240h-40v-80l40-200h640l40 200v80h-40v240h-80v-240H560v240H160Zm80-80h240v-160H240v160Z" />
                        </svg>
                        <h5 class="card-title fw-bold mb-0">Cabang</h5>
                        <p class="card-text">Tambah, Edit, Delete Cabang</p>
                        <a href="{{ route('dashboard.branches') }}" class="btn btn-danger background-red">Masuk</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100 border-red shadow">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px"
                            fill="#333333">
                            <path
                                d="M40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm720 0v-120q0-44-24.5-84.5T666-434q51 6 96 20.5t84 35.5q36 20 55 44.5t19 53.5v120H760ZM360-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm400-160q0 66-47 113t-113 47q-11 0-28-2.5t-28-5.5q27-32 41.5-71t14.5-81q0-42-14.5-81T544-792q14-5 28-6.5t28-1.5q66 0 113 47t47 113Z" />
                        </svg>
                        <h5 class="card-title fw-bold mb-0">Pengguna</h5>
                        <p class="card-text">Kelola pengguna website</p>
                        <a href="{{ route('dashboard.users') }}" class="btn btn-danger background-red">Masuk</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                <div class="card text-center h-100 border-red shadow">
                    <div class="card-body">
                        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px"
                            fill="#333333">
                            <path
                                d="M320-280q17 0 28.5-11.5T360-320q0-17-11.5-28.5T320-360q-17 0-28.5 11.5T280-320q0 17 11.5 28.5T320-280Zm0-160q17 0 28.5-11.5T360-480q0-17-11.5-28.5T320-520q-17 0-28.5 11.5T280-480q0 17 11.5 28.5T320-440Zm0-160q17 0 28.5-11.5T360-640q0-17-11.5-28.5T320-680q-17 0-28.5 11.5T280-640q0 17 11.5 28.5T320-600Zm120 320h240v-80H440v80Zm0-160h240v-80H440v80Zm0-160h240v-80H440v80ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Z" />
                        </svg>
                        <h5 class="card-title fw-bold mb-0">Pesanan</h5>
                        <p class="card-text">History pesanan member</p>
                        <a href="{{ route('dashboard.orders') }}" class="btn btn-danger background-red">Masuk</a>
                    </div>
                </div>
            </div>
        </div>
    @endsection
