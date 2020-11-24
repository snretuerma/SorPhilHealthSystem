<template>
<div>
    <div class="row header-top">
        <div class="header-title-parent">
            <span class="header-title">
                <i class="fa fa-user-md"></i>&nbsp;&nbsp;Staff List
            </span>
        </div>
    </div>
    <div id="search_box" class="row">
        <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
            <el-input
                prefix-icon="el-icon-search"
                v-model="search"
                placeholder="Type to search"
            />
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <el-button
                        class="btn-action block-button"
                        @click="handleSearchClick"
                    >
                        Add
                    </el-button>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <el-dropdown @command="formDialog" class="block-button btn-action">
                        <el-button>
                            Excel
                            <i class="el-icon-arrow-down el-icon--right"/>
                        </el-button>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item
                                icon="el-icon-upload2"
                                command="import_data"
                            >
                                Import Data
                            </el-dropdown-item>
                            <el-dropdown-item
                                icon="el-icon-download"
                                command="export_data"
                            >
                                Export Data
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <el-table v-loading="loading" :data="listData">
                            <el-table-column label="Name" prop="name"></el-table-column>
                            <el-table-column
                                label="Employment Type"
                                prop="is_parttime"
                                align="center"
                                :formatter="employmentType"
                            ></el-table-column>
                            <el-table-column
                                label="Employment Status"
                                prop="is_active"
                                align="center"
                                :formatter="employmentStatus"
                            ></el-table-column>
                            <el-table-column width="135" align="center" fixed="right">
                                <template slot="header"> Action </template>
                                <template slot-scope="scope">
                                    <!-- <el-tooltip
                                        class="item"
                                        effect="light"
                                        content="View"
                                        placement="top"
                                        :enterable="false"
                                    >
                                        <el-button
                                            size="mini"
                                            type="info"
                                            icon="el-icon-info"
                                            circle
                                            @click="handleView(scope.$index, scope.row)"
                                        ></el-button>
                                    </el-tooltip> -->
                                    <el-tooltip
                                        class="item"
                                        effect="light"
                                        content="Edit"
                                        placement="top"
                                        :enterable="false"
                                    >
                                        <el-button
                                            size="mini"
                                            type="primary"
                                            icon="el-icon-edit"
                                            circle
                                            @click="handleEdit(scope.$index, scope.row, 'edit')"
                                        ></el-button>
                                    </el-tooltip>
                                    <el-tooltip
                                        class="item"
                                        effect="light"
                                        content="Delete"
                                        placement="top"
                                        :enterable="false"
                                    >
                                        <el-button
                                            size="mini"
                                            type="danger"
                                            icon="el-icon-delete"
                                            circle
                                            @click="handleDelete(scope.$index, scope.row)"
                                        ></el-button>
                                    </el-tooltip>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</template>

<script>
'use strict'
export default {
    data() {
        return {
            loading: true,
            doctors: {},
        }
    },
    methods: {
        getDoctors() {
            axios.get("doctors_get")
            .then((response) => {
                this.doctors = response.data;
                this.loading = false;
            })
            .catch(function (error) {
                this.$notify({
                    type: 'error',
                    title: 'Loading Error',
                    message: 'Failed to load doctor\'s master list',
                    offset: 0,
                });
            });
        },
        handleSearch() {

        }
    },
    mounted() {
        this.getDoctors();
    }
}
</script>

<style lang="css">
    #search_box {
        margin-bottom: 10px;
    }
    .block-button, .block-button button {
        width: 100%;
    }
</style>