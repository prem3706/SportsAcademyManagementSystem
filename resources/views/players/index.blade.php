<x-layout>
    <x-sidebar />

    <div class="wrapper d-flex flex-column min-vh-100 bg-body-tertiary">

        <x-navbar />

        <div class="container-lg py-3">

            <x-table-crud-card heading="Players Management" subheading="Manage all Players" title="Add Player"
                :url="route('players.create')" id="addPlayerBtn" statusFilter="True">
                {{-- {{ $dataTable->table(['class' => 'table table-hover align-middle mb-0']) }} --}}

            </x-table-crud-card>


        </div>

    </div>
    {{--
    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush --}}

</x-layout>
