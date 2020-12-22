<template>
    <div>
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Summary of
                    Doctor's Performance Base
                </span>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    placeholder="Type to search"
                />
            </div>
            <div class="col-xl-5 col-lg-5 col-md-6 col-sm-12">
                <el-form ref="form">
                    <el-form-item prop="batch">
                        <el-select
                            ref="defaultValue"
                            :required="true"
                            v-model="value"
                            class="block-button"
                            size="large"
                            value-key="batch"
                            multiple
                            :multiple-limit="1"
                            filterable
                            default-first-option
                            allow-create
                            @change="changes"
                        >
                            <el-option
                                v-for="item in batch"
                                :key="item.batch"
                                :label="item.label"
                                :value="item.batch"
                            >
                                {{ item.batch }}</el-option
                            >
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <el-button icon="el-icon-download" style="width:100%" @click="exportSummary">Export</el-button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <el-tabs type="border-card">
                    <el-tab-pane label="Public">
                        <div class="row">
                            <div class="col-12">
                                <el-table
                                    :data="listData"
                                    :sort-by="['name']"
                                    @sort-change="changeTableSort1"
                                    border
                                >
                                    <el-table-column
                                        min-width="300"
                                        label="Name of Physician"
                                        prop="name"
                                        :sortable="'custom'"
                                    ></el-table-column>
                                    <el-table-column label="50%">
                                        <el-table-column
                                            min-width="150"
                                            label="Nursing Services"
                                            :formatter="formatNumber"
                                            prop="nursing_services"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="150"
                                            label="Non-medical"
                                            :formatter="formatNumber"
                                            prop="non_medical"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="140"
                                            label="Total"
                                            :formatter="formatNumber"
                                            prop="fifty_total"
                                            :sortable="'custom'"
                                        ></el-table-column>
                                    </el-table-column>
                                    <el-table-column
                                        label="Performance Based Sharing"
                                    >
                                        <el-table-column
                                            min-width="170"
                                            label="Doctors Share (35%)"
                                            :formatter="formatNumber"
                                            prop="doctors_share"
                                            :sortable="'custom'"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="150"
                                            label="Pooled (15%)"
                                            :formatter="formatNumber"
                                            prop="pooled"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="140"
                                            label="Total"
                                            :formatter="formatNumber"
                                            prop="pbs_total"
                                        ></el-table-column>
                                    </el-table-column>
                                </el-table>
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
                        <br />
                        <div class="row">
                            <div class="col-12">
                                <el-table
                                    :data="sumOfAll"
                                    border
                                    :row-class-name="tableRowClassName"
                                >
                                    <el-table-column
                                        label="Nursing Services Total"
                                        :formatter="formatNumber"
                                        min-width="150"
                                        prop="nursing_services_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Non-medical Total"
                                        :formatter="formatNumber"
                                        min-width="150"
                                        prop="non_medical_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Total"
                                        :formatter="formatNumber"
                                        min-width="140"
                                        prop="fifty_total_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Doctor Share Total"
                                        :formatter="formatNumber"
                                        min-width="170"
                                        prop="doctors_share_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Pooled Total"
                                        :formatter="formatNumber"
                                        min-width="150"
                                        prop="pooled_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Total"
                                        :formatter="formatNumber"
                                        min-width="140"
                                        prop="pbs_total_total"
                                    ></el-table-column>
                                    <el-table-column
                                        label="Grand Total"
                                        :formatter="formatNumber"
                                        min-width="180"
                                        prop="grand_total"
                                    >
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane label="Private">
                        <div class="row">
                            <div class="col-12">
                                <el-table
                                    :data="privateDoctors"
                                    @sort-change="changeTableSort3"
                                    border
                                >
                                    <el-table-column
                                        label="Private Doctor Records"
                                    >
                                        <el-table-column
                                            min-width="300"
                                            label="Name of Physician"
                                            prop="name"
                                            sortable
                                        ></el-table-column>
                                        <el-table-column label="50%">
                                            <el-table-column
                                                min-width="150"
                                                label="Nursing Services"
                                                :formatter="formatNumber"
                                                prop="nursing_services"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="150"
                                                label="Non-medical"
                                                :formatter="formatNumber"
                                                prop="non_medical"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="140"
                                                label="Total"
                                                :formatter="formatNumber"
                                                prop="fifty_total"
                                                :sortable="'custom'"
                                            ></el-table-column>
                                        </el-table-column>
                                        <el-table-column
                                            label="Performance Based Sharing"
                                        >
                                            <el-table-column
                                                min-width="170"
                                                label="Doctors Share (35%)"
                                                :formatter="formatNumber"
                                                prop="doctors_share"
                                                :sortable="'custom'"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="150"
                                                label="Pooled (15%)"
                                                :formatter="formatNumber"
                                                prop="pooled"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="140"
                                                label="Total"
                                                :formatter="formatNumber"
                                                prop="pbs_total"
                                            ></el-table-column>
                                        </el-table-column>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </el-tab-pane>
                </el-tabs>
            </div>
        </div>
    </div>
</template>
<style>
.el-table .success-row {
    background: #a0faa6;
}
</style>

<script>
import XLSX from 'xlsx';
export default {
    data() {
        return {
            active: [],
            sumOfAll: [],
            batch: [],
            value: [],
            privateDoctors: [],
            nursing_services_total: 0,
            non_medical_total: 0,
            fifty_total_total: 0,
            doctors_share_total: 0,
            pooled_total: 0,
            pbs_total_total: 0,
            grand_total: 0,
            search: "",
            page: 1,
            pageSize: 10,
            sheet_data: {
                    A1:{t:'s', v:"SUMMARY OF DOCTOR'S PERFORMANCE BASE"},
                    A2:{t:'s', v:"COVERED PERIOD:"},
                    A3:{t:'s', v:"NAME OF PHYSICIAN"},
                    B3:{t:'s', v:"50%"},
                    B4:{t:'s', v:"NURSING SERVICES"},
                    C4:{t:'s', v:"NONE-MEDICAL"},
                    D4:{t:'s', v:"TOTAL"},
                    E3:{t:'s', v:"PERFORMANCE BASED SHARING"},
                    E4:{t:'s', v:"DOCTORS SHARE (35%)"},
                    F4:{t:'s', v:"POOLED (15%)"},
                    G4:{t:'s', v:"TOTAL"},
                    H3:{t:'s', v:"SIGNATURE"},
                    "!merges":[
                        {s:{r:0,c:0},e:{r:0,c:7}},
                        {s:{r:1,c:0},e:{r:1,c:7}},
                        {s:{r:2,c:0},e:{r:3,c:0}},
                        {s:{r:2,c:1},e:{r:2,c:3}},
                        {s:{r:2,c:4},e:{r:2,c:6}},
                        {s:{r:2,c:7},e:{r:3,c:7}},
                    ],
                    "!ref": "A1:H5",
            },
            sheet_data_private: {
                    A1:{t:'s', v:"SUMMARY OF DOCTOR'S PERFORMANCE BASE"},
                    A2:{t:'s', v:"COVERED PERIOD:"},
                    A3:{t:'s', v:"NAME OF PHYSICIAN"},
                    B3:{t:'s', v:"50%"},
                    B4:{t:'s', v:"NURSING SERVICES"},
                    C4:{t:'s', v:"NONE-MEDICAL"},
                    D4:{t:'s', v:"TOTAL"},
                    E3:{t:'s', v:"PERFORMANCE BASED SHARING"},
                    E4:{t:'s', v:"DOCTORS SHARE (35%)"},
                    F4:{t:'s', v:"POOLED (15%)"},
                    G4:{t:'s', v:"TOTAL"},
                    H3:{t:'s', v:"SIGNATURE"},
                    "!merges":[
                        {s:{r:0,c:0},e:{r:0,c:7}},
                        {s:{r:1,c:0},e:{r:1,c:7}},
                        {s:{r:2,c:0},e:{r:3,c:0}},
                        {s:{r:2,c:1},e:{r:2,c:3}},
                        {s:{r:2,c:4},e:{r:2,c:6}},
                        {s:{r:2,c:7},e:{r:3,c:7}},
                    ],
                    "!ref": "A1:H5",
            }
        };
    },
    computed: {
        searching() {
            if (!this.search) {
                this.total = this.active.length;
                return this.active;
            }
            this.page = 1;
            return this.active.filter(active =>
                active.name.toLowerCase().includes(this.search.toLowerCase())
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
        formatNumber(row, column, cellValue, index){
           return new Intl.NumberFormat().format(cellValue)
        },
        changes() {
            if (this.value != "") {
                this.privateDoctors = [];
                this.active = [];
                this.sumOfAll = [];
                this.nursing_services_total = 0;
                this.non_medical_total = 0;
                this.fifty_total_total = 0;
                this.doctors_share_total = 0;
                this.pooled_total = 0;
                this.pbs_total_total = 0;
                this.grand_total = 0;
                this.getSummary(this.value);
            }
        },
        getBatch() {
            axios
            .get("get_batch")
            .then(response => {
                response.data.push({ batch: "All" });
                this.batch = response.data;
                this.value[0] = response.data[0].batch;
                this.first_batch = response.data[0].batch;
                this.getSummary(response.data[0].batch);
            })
            .catch(function(error) {});
        },
        tableRowClassName({ row, rowIndex }) {
            return "success-row";
        },
        changeTableSort1(column) {
            var fieldName = column.prop;
            var sortingType = column.order;

            if (sortingType == "descending") {
                this.data = this.active.sort(
                    (a, b) => b[fieldName] - a[fieldName]
                );
            } else {
                this.data = this.active.sort(
                    (a, b) => a[fieldName] - b[fieldName]
                );
            }
        },
        changeTableSort3(column) {
            var fieldName = column.prop;
            var sortingType = column.order;

            if (sortingType == "descending") {
                this.data = this.privateDoctors.sort(
                    (a, b) => b[fieldName] - a[fieldName]
                );
            }else {
                this.data = this.privateDoctors.sort(
                    (a, b) => a[fieldName] - b[fieldName]
                );
            }
        },
        getSummary: function(batch) {
            axios
            .get(`get_summary/${batch}`)
            .then(response => {
                response.data.forEach(doctor => {
                    doctor.nursing_services = 0;
                    doctor.non_medical = 0;
                    doctor.fifty_total = 0;
                    doctor.doctors_share = 0;
                    doctor.pooled = 0;
                    doctor.pbs_total = 0;
                    doctor.record_type = "";
                    doctor.credit_records.forEach(patient => {
                        doctor.doctors_share += Number(
                            patient.pivot.professional_fee
                        );
                        if (patient.pooled_record == null) {
                            doctor.pooled = 0;
                        } else {
                            if (
                                doctor.is_parttime == 0 &&
                                JSON.parse(
                                    doctor.credit_records[0].pooled_record
                                        .full_time_doctors
                                ).includes(doctor.id)
                            ) {
                                doctor.pooled = Number(
                                    patient.pooled_record
                                        .full_time_individual_fee
                                );
                            } else {
                                doctor.pooled = Number(
                                    patient.pooled_record
                                        .part_time_individual_fee
                                );
                            }
                        }
                        doctor.pbs_total = Number(
                            doctor.doctors_share + doctor.pooled
                        );
                        doctor.nursing_services += Number(
                            patient.medical_fee
                        );
                        doctor.non_medical += Number(
                            patient.non_medical_fee
                        );
                        doctor.fifty_total = Number(
                            doctor.nursing_services + doctor.non_medical
                        );
                        doctor.record_type = patient.record_type;
                    });
                    if (doctor.pooled != 0) {
                        this.nursing_services_total +=
                            doctor.nursing_services;
                        this.non_medical_total += doctor.non_medical;
                        this.fifty_total_total += doctor.fifty_total;
                        this.doctors_share_total += Number(
                            doctor.doctors_share
                        );
                        this.pooled_total += Number(doctor.pooled);
                        this.pbs_total_total += Number(doctor.pbs_total);
                        this.grand_total =
                            this.fifty_total_total + this.pbs_total_total;
                    }
                        if(doctor.record_type == "private"){
                            if (doctor.fifty_total || doctor.pbs_total != 0) {
                                this.privateDoctors.push(doctor);
                            }
                        }
                        else
                         if (doctor.fifty_total || doctor.pbs_total != 0) {
                            this.active.push(doctor);
                         }
                });
                console.log(this.privateDoctors);
                this.sumOfAll.push({
                    nursing_services_total: this.nursing_services_total,
                    non_medical_total: this.non_medical_total,
                    fifty_total_total: this.fifty_total_total,
                    doctors_share_total: this.doctors_share_total.toFixed(
                        4
                    ),
                    pooled_total: this.pooled_total.toFixed(4),
                    pbs_total_total: this.pbs_total_total.toFixed(4),
                    grand_total: this.grand_total.toFixed(4)
                });
                this.data = response.data;
            })
            .catch(function(error) {});
        },
        handleCurrentChange(val) {
            this.page = val;
        },
        exportSummary() {
            var row = 4;
            var prow = 4;
            this.active.forEach((physician)=>{
                row += 1;
                this.sheet_data["A"+row] = {t: 's', v: physician.name};
                this.sheet_data["B"+row] = {t: 'n', v: physician.nursing_services};
                this.sheet_data["C"+row] = {t: 'n', v: physician.non_medical};
                this.sheet_data["D"+row] = {t: 'n', v: physician.fifty_total};
                this.sheet_data["E"+row] = {t: 'n', v: physician.doctors_share};
                this.sheet_data["F"+row] = {t: 'n', v: physician.pooled};
                this.sheet_data["G"+row] = {t: 'n', v: physician.pbs_total};
            });
            this.privateDoctors.forEach((physician)=>{
                prow += 1;
                this.sheet_data_private["A"+prow] = {t: 's', v: physician.name};
                this.sheet_data_private["B"+prow] = {t: 'n', v: physician.nursing_services};
                this.sheet_data_private["C"+prow] = {t: 'n', v: physician.non_medical};
                this.sheet_data_private["D"+prow] = {t: 'n', v: physician.fifty_total};
                this.sheet_data_private["E"+prow] = {t: 'n', v: physician.doctors_share};
                this.sheet_data_private["F"+prow] = {t: 'n', v: physician.pooled};
                this.sheet_data_private["G"+prow] = {t: 'n', v: physician.pbs_total};
            });
            row += 2;
            this.sheet_data["A"+row] = {t: 's', v: "TOTAL"};
            this.sheet_data["B"+row] = {t: 'n', v: this.nursing_services_total};
            this.sheet_data["C"+row] = {t: 'n', v: this.non_medical_total};
            this.sheet_data["D"+row] = {t: 'n', v: this.fifty_total_total};
            this.sheet_data["E"+row] = {t: 'n', v: this.doctors_share_total};
            this.sheet_data["F"+row] = {t: 'n', v: this.pooled_total};
            this.sheet_data["G"+row] = {t: 'n', v: this.pbs_total_total};
            row += 1;
            this.sheet_data["G"+row] = {t: 's', v: "GRAND TOTAL"};
            this.sheet_data["H"+row] = {t: 'n', v: this.grand_total};
            this.sheet_data['!ref'] = "A1:H" + row;
            this.sheet_data_private['!ref'] = "A1:H" + prow;
            var sheet_name;
            if (typeof this.value[0] !== 'undefined' || this.value[0] == 'All') {
                if (this.value[0] == 'All') {
                    sheet_name = "All Record";
                } else {
                    var month_name = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'];
                    var d = (this.value[0]).trim().split('-');
                    var date_from = month_name[parseInt(d[0][2]+d[0][3]) - 1] + " " + d[0][0]+d[0][1]+" "+d[0][4]+d[0][5]+d[0][6]+d[0][7];
                    var date_to = month_name[parseInt(d[1][2]+d[1][3]) - 1] + " " + d[1][0]+d[1][1]+" "+d[1][4]+d[1][5]+d[1][6]+d[1][7];
                    sheet_name = date_from + " - " + date_to;
                    this.sheet_data.A2.v = "COVERED PERIOD: " + sheet_name.toUpperCase();
                    this.sheet_data_private.A2.v = "COVERED PERIOD: " + sheet_name.toUpperCase();
                }
                var sheet_data_object = {};
                sheet_data_object[sheet_name] = this.sheet_data;
                sheet_data_object['Private'] = this.sheet_data_private;
                XLSX.writeFile({
                    SheetNames:[sheet_name, 'Private'],
                    Sheets: sheet_data_object
                }, 'Summary_Export.xlsx');
            } else {
                this.$notify({
                    type: 'warning',
                    title: 'Export',
                    message: "Please select batch to proceed",
                });
            }
        }
    },
    mounted() {
        this.getBatch();
    }
};
</script>
