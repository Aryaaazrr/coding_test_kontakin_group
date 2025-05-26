<script setup lang="ts">
import { Settings2 } from "lucide-vue-next";
import type {
    ColumnDef,
    ColumnFiltersState,
    ExpandedState,
    SortingState,
    VisibilityState,
} from "@tanstack/vue-table";
import {
    FlexRender,
    getCoreRowModel,
    getExpandedRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
    getGlobalFacetedRowModel,
    getFacetedUniqueValues,
} from "@tanstack/vue-table";
import { ArrowUpDown, ChevronDown } from "lucide-vue-next";
import { h, ref, computed } from "vue";
import { valueUpdater } from "@/lib/utils.js";

import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { Input } from "@/components/ui/input";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import DropdownAction from "./DataTableDropDown.vue";

const props = defineProps<{
    data: any[];
    columns: ColumnDef<any>[];
    filterKey?: string;
    filterPlaceholder?: string;
    showExpand?: boolean;
    pagination?: boolean;
}>();

const filterKey = props.filterKey;
const filterPlaceholder = props.filterPlaceholder ?? "Filter...";
const showExpand = props.showExpand ?? false;
const enablePagination = props.pagination ?? true;
const globalFilter = ref("");

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const expanded = ref<ExpandedState>({});

const table = useVueTable({
    data: props.data,
    columns: props.columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getExpandedRowModel: getExpandedRowModel(),
    getFacetedUniqueValues: getFacetedUniqueValues(),
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onColumnFiltersChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnFilters),
    onColumnVisibilityChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, columnVisibility),
    onRowSelectionChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, rowSelection),
    onExpandedChange: (updaterOrValue) =>
        valueUpdater(updaterOrValue, expanded),
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
        get expanded() {
            return expanded.value;
        },
    },
});
</script>

<template>
    <div class="w-full">
        <div class="flex items-center py-4 gap-2">
            <Input
                class="max-w-sm"
                :placeholder="filterPlaceholder"
                :model-value="table.getColumn(filterKey)?.getFilterValue() as string"
                @update:model-value="
                    table.getColumn(filterKey)?.setFilterValue($event)
                "
            />
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline" class="ml-auto">
                        <Settings2 />
                        View <ChevronDown class="ml-2 h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem
                        v-for="column in table
                            .getAllColumns()
                            .filter((c) => c.getCanHide())"
                        :key="column.id"
                        class="capitalize"
                        :model-value="column.getIsVisible()"
                        @update:model-value="column.toggleVisibility(!!$event)"
                    >
                        {{ column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow
                        v-for="headerGroup in table.getHeaderGroups()"
                        :key="headerGroup.id"
                    >
                        <TableHead
                            v-for="header in headerGroup.headers"
                            :key="header.id"
                        >
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <template
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                        >
                            <TableRow
                                :data-state="row.getIsSelected() && 'selected'"
                            >
                                <TableCell
                                    v-for="cell in row.getVisibleCells()"
                                    :key="cell.id"
                                >
                                    <FlexRender
                                        :render="cell.column.columnDef.cell"
                                        :props="cell.getContext()"
                                    />
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="showExpand && row.getIsExpanded()">
                                <TableCell :colspan="row.getAllCells().length">
                                    <pre>{{
                                        JSON.stringify(row.original, null, 2)
                                    }}</pre>
                                </TableCell>
                            </TableRow>
                        </template>
                    </template>
                    <TableRow v-else>
                        <TableCell
                            :colspan="props.columns.length || 1"
                            class="h-24 text-center"
                        >
                            No results.
                        </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div
            class="flex items-center justify-between py-4 text-sm text-muted-foreground"
        >
            <div>
                {{ table.getFilteredSelectedRowModel().rows?.length || 0 }} of
                {{ table.getFilteredRowModel().rows?.length || 0 }} row(s)
                selected.
            </div>

            <template v-if="enablePagination">
                <div class="flex items-center gap-2">
                    <span>
                        Page {{ table.getState().pagination.pageIndex + 1 }} of
                        {{ table.getPageCount() }}
                    </span>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!table.getCanPreviousPage()"
                        @click="table.previousPage()"
                    >
                        Previous
                    </Button>
                    <Button
                        variant="outline"
                        size="sm"
                        :disabled="!table.getCanNextPage()"
                        @click="table.nextPage()"
                    >
                        Next
                    </Button>
                </div>
            </template>
        </div>
    </div>
</template>
