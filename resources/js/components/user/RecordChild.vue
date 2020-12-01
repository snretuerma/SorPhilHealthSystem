<template>
    <div>
         <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Records
                </span>
            </div>
        </div>
        <!-- End Header -->
        <!-- Search Box -->
        <div class="row" id="search_box" style="margin-bottom: 10px">
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
                            @click="triggerAdd"
                        >
                            Add
                        </el-button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <el-dropdown  class="block-button btn-action">
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
        </div>
        <!-- Search End -->
        <!-- table -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div id="test">
                            <el-table :data="listData">
                                <el-table-column
                                    width="200"
                                    label="Patient"
                                    prop="patient_name"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="177"
                                    label="Admission Date"
                                    prop="admission_date"
                                    column-key="ase"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="177"
                                    label="Discharge Date"
                                    prop="discharge_date"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="110"
                                    label="PF"
                                    prop="total"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="140"
                                    label="Attending"
                                    prop="allattending"
                                
                                >
                                </el-table-column>
                                <el-table-column
                                    width="115"
                                    label="Requesting"
                                    prop="allrequesting"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Surgeon"
                                    prop="allsurgeon"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Healthcare"
                                    prop="allhealthcare"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="ER"
                                    prop="aller"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="130"
                                    label="Anesthesiologist"
                                    prop="allanesthesiologist"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="130"
                                    label="Co-management"
                                    prop="allcomanagement"
                                >
                                </el-table-column>
                                <el-table-column
                                    min-width="115"
                                    label="Admitting"
                                    prop="alladmitting"
                                >
                                </el-table-column>
                                <el-table-column
                                    width="100"
                                    align="center"
                                    fixed="right"
                                >
                                    <template slot="header">
                                        Action
                                    </template>
                                    <template slot-scope="scope">
                                        <el-tooltip
                                            class="item"
                                            effect="light"
                                            content="Add Contribution"
                                            placement="top"
                                            v-if="scope.row.totalPersonnel == 0"
                                            :enterable = false
                                        >
                                            <el-button
                                                size="mini"
                                                type="success"
                                                icon="el-icon-plus"
                                                circle
                                            >
                                            </el-button>
                                        </el-tooltip>
                                        <el-tooltip
                                            class="item"
                                            effect="light"
                                            content="view"
                                            placement="top"
                                            v-if="scope.row.totalPersonnel > 0"
                                            :enterable = false
                                        >
                                            <el-button
                                                size="mini"
                                                type="info"
                                                icon="el-icon-info"
                                                circle
                                            >
                                            </el-button>
                                        </el-tooltip>
                                        <el-tooltip
                                            class="item"
                                            effect="light"
                                            content="delete"
                                            placement="top"
                                            :enterable = false
                                        >
                                            <el-button
                                                size="mini"
                                                type="danger"
                                                icon="el-icon-delete"
                                                circle
                                            ></el-button>
                                        </el-tooltip>
                                    </template>
                                </el-table-column>
                            </el-table>
                        <div align="center">
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
                </div>
            </div>
        </div>
        <!-- end table -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            search: "",
            data:[],
            page: 1,
            pageSize: 10,
            total:"",
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
                    data.first_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.last_name
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.philhealth_number
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.final_diagnosis
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    data.record_type
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
         triggerAdd() {
             //add-trigger the funtion in parent with parameter
            this.$emit("add-open", "hi");
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        getRecords(){
             axios
                .get("get_records")
                .then(response => {
                    response.data.forEach(record => {
                        record.allattending="";
                        record.allrequesting="";
                        record.allsurgeon="";
                        record.allhealthcare="";
                        record.aller="";
                        record.allanesthesiologist="";
                        record.allcomanagement="";
                        record.alladmitting="";
                        record.doctors.forEach(doctor=>{
                            if(doctor.pivot.doctor_role=="attending") {
                                record.allattending+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="requesting") {
                                record.allrequesting+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="surgeon") {
                                record.allsurgeon+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="healthcare") {
                                record.allhealthcare+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="er") {
                                record.aller+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="anesthesiologist") {
                                record.allanesthesiologist+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="comanagement") {
                                record.allcomanagement+=doctor.name+", ";
                            }
                            if(doctor.pivot.doctor_role=="admitting") {
                                record.alladmmitting+=doctor.name+", ";
                            }
                        });
                    });
                    this.data = response.data;
                })
                .catch(function(error) {});
        },
    },
    mounted(){
        this.getRecords();
    }
}
</script>
<style scoped>
  /*add scoped styles here*/
  #search_box {
        margin-bottom: 10px;
    }
    .block-button, .block-button button {
        width: 100%;
    }
</style>
