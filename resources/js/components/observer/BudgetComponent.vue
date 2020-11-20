<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-coins"></i>&nbsp;&nbsp;Budget List
                </span>
            </div>
        </div>
        <!-- End Header -->

        <!-- Search Box -->
        <div class="row" style="margin-bottom: 10px">
            <div class="col-sm-6">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>
        <!-- Search End -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table v-loading="loading" :data="listData">
                    <el-table-column
                        width="250"
                        label="Start Date"
                        prop="start_date"
                    ></el-table-column>
                    <el-table-column
                        width="250"
                        label="Amount"
                        prop="total"
                    ></el-table-column>
                    <el-table-column
                        width="250"
                        label="End date"
                        prop="end_date"
                    ></el-table-column>
                    <el-table-column
                        min-width="250"
                        label="Hospital"
                        prop="hospital_code"
                    ></el-table-column>
                    <el-table-column width="60" align="center" fixed="right">
                        <template slot="header">
                            Action
                        </template>
                        <template slot-scope="scope">
                            <el-tooltip
                                class="item"
                                effect="light"
                                content="View"
                                placement="top"
                                :enterable=false
                            >
                                <el-button
                                    size="mini"
                                    type="info"
                                    icon="el-icon-info"
                                    circle
                                    @click="handleView(scope.$index, scope.row)"
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>
                <!-- Table End -->
                <div style="text-align: center">
                    <el-pagination
                        background
                        layout="prev, pager, next"
                        @current-change="handleCurrentChange"
                        :page-size="pageSize"
                        :total="total"
                    >
                    </el-pagination>
                </div>
            </div>
        </div>
        <!-- Card Ends Here -->

        <!-- Footer -->
        <hr />
        <div class="footer">
            <div class="containter-fluid">
                <div class="row text-center">
                    <span class="text-muted"
                        >&nbsp;&nbsp;&nbsp;&nbsp;©PF Management System
                        2020</span
                    >
                </div>
            </div>
        </div>
        <!-- Footer ends -->

        <!-- Show Budget Details -->
        <el-dialog title="Staff Info" :visible.sync="dialogTableVisible">
            <el-table :data="gridData">
                <el-table-column
                    property="start_date"
                    label="Start Date"
                    width="180"
                ></el-table-column>
                <el-table-column
                    property="total"
                    label="Amount"
                    width="180"
                ></el-table-column>
                <el-table-column
                    property="end_date"
                    label="End Date"
                    width="180"
                ></el-table-column>
                <el-table-column
                    property="hospital_code"
                    label="Hospital"
                    width="180"
                ></el-table-column>
            </el-table>
        </el-dialog>
        <!-- Show Budget Details -->
    </div>
</template>
<script>
"use strict";
import constants from "../../constants.js";
export default {
    data() {
        return {
            page: 1,
            pageSize: 10,
            loading: true,
            data: [],
            search: "",
            dialogTableVisible: false,
            // View info data
            gridData: [
                {
                    start_date: "",
                    total: "",
                    end_date: "",
                    hospital_code: ""
                }
            ]
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.data.length;
                return this.data;
            }
            this.page = 1;
            return this.data.filter(
                data =>
                    data.start_date
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.total
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.end_date
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.hospital_code
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
            );
        },
        listData() {
            this.total = this.searching.length;

            return this.searching.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        }
    },
    methods: {
        masknumber: function(num) {
            num = parseFloat(num)
                .toFixed(2)
                .replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
            return num;
        },
        handleView(index, row) {
            this.dialogTableVisible = true;
            this.gridData[0].start_date = row.start_date;
            this.gridData[0].total = row.total;
            this.gridData[0].end_date = row.end_date;
            this.gridData[0].hospital_code = row.hospital_code;
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        getBudgetList: function() {
            axios
                .get("observerBudgetList")
                .then(response => {
                    response.data.forEach(entry => {
                        entry.total = this.masknumber(entry.total);
                    });
                    this.data = response.data;
                    // console.log(this.data);
                    this.loading = false;
                })
                .catch(function(error) {});
        }
    },
    mounted() {
        this.getBudgetList();
    }
};
</script>
