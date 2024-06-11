<section class="w-100 mb-3 rounded-4 shadow-lg ">
    <div class="p-3">
        <div class="card-nama">
            <span class="font-10">Nama Member</span>
            <h2 class="fw-bold font-14">{{ auth()->user()->name }}</h2>
        </div>
        <div class="d-flex justify-content-between align-items-center ">
            <div>
                <span class="fw-semibold font-10">Poin</span>
                <h2 class="font-10">{{ auth()->user()->user_points }}</h2>
            </div>
            <img src="/img/Collaboration 2.png" alt="F&B Caatis" height="24">
        </div>
    </div>
</section>
