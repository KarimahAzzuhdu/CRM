@extends('layout.main')
@section('title','Sales Visit')

@section('content')
<div class="container-expanded mx-auto px-6 lg:px-8 py-8 pt-[60px] mt-4">

    <!-- Sales Visit Card dengan Everything Inside -->
    <div style="background-color: #ffffff; border-radius: 0.5rem; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb; overflow: hidden;">

        <!-- Card Header dengan Title dan Action Buttons -->
        <div style="padding: 0.5rem 1.5rem; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: #111827; margin: 0;">Sales Visit Management</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0 0 0;">Kelola data kunjungan sales dan informasinya</p>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    @if(auth()->user()->canAccess($currentMenuId, 'create'))
                    <button onclick="openVisitModal()"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2); transition: all 0.2s;">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Kunjungan</span>
                    </button>
                    @endif

                    <!--
                    <a href="{{ route('salesvisit.export') }}"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; text-decoration: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2); transition: all 0.2s;">
                        <i class="fas fa-file-export"></i>
                        <span>Export</span>
                    </a>
                    -->

                    <!--
                    <button onclick="openImportModal()"
                        style="display: flex; align-items: center; gap: 0.5rem; padding: 0.625rem 1rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 0.5rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2); transition: all 0.2s;">
                        <i class="fas fa-file-import"></i>
                        <span>Import</span>
                    </button>
                    -->
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div style="padding: 0.5rem 1.5rem; background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center;">
                <x-globals.filtersearch
                    tableId="salesVisitTable"
                    :columns="[
                        'number',
                        'sales',
                        'pic',
                        'company',
                        'location',
                        'visit_date',
                        'purpose',
                        'follow_up',
                        'actions'
                    ]"
                    :filters="[
                        'Sales' => $salesUsers,
                        'Province' => $provinces
                    ]"
                    ajaxUrl="{{ route('salesvisit.search') }}"
                    placeholder="Cari pic, company, atau alamat..."
                />
            </div>
        </div>

        <!-- Table Section - NO PADDING! -->
        <x-salesvisit.table.table :salesVisits="$salesVisits" :currentMenuId="$currentMenuId" :types="$types"/>

        <!-- Pagination -->
        @if($salesVisits->hasPages())
        <div style="border-top: 1px solid #e5e7eb; background-color: #f9fafb;">
            <x-globals.pagination :paginator="$salesVisits" />
        </div>
        @endif
    </div>
</div>

<!-- Modals -->
<x-salesvisit.action.action :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" :types="$types"/>
<x-salesvisit.action.edit :currentMenuId="$currentMenuId" :salesUsers="$salesUsers" :provinces="$provinces" :types="$types" />

@push('scripts')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/address-cascade.js') }}"></script>
<script src="{{ asset('js/salesvisit-modal.js') }}"></script>
@endpush

<style>
    /* Hover effects for buttons */
    button:hover, a[href]:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Focus styles for inputs */
    #searchInput:focus,
    #filterFollowUp:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        button span, a span {
            display: none;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('🚀 SalesVisit page loaded');

    if (typeof TableHandler === 'undefined') {
        console.error('❌ TableHandler class not found. search.js may not be loaded.');
        return;
    }

    console.log('✅ Creating TableHandler instance...');

    try {
        window.salesVisitTableHandler = new TableHandler({
            tableId: 'salesVisitTable',
            ajaxUrl: '{{ route("salesvisit.search") }}',
            filters: ['sales', 'province'],
            // ✅ FIXED: Urutan sesuai table header
            columns: ['number', 'visit_date', 'company', 'pic', 'location', 'purpose', 'sales', 'follow_up', 'actions'],
            searchParam: 'q'
        });

        console.log('✅ TableHandler initialized successfully');

    } catch (error) {
        console.error('❌ Error initializing TableHandler:', error);
    }
});
</script>
@endsection
