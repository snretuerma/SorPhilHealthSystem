<template>
    <div>
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Dashboard /
                    Summary of Doctor's Performance Base
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
                <el-button
                    ref="summary"
                    id="summary"
                    style="width:100%"
                    @click="exportSummary"
                    >Export</el-button
                >
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <el-tabs type="border-card">
                    <el-tab-pane label="Public">
                        <div class="row">
                            <div class="col-12">
                                <el-table
                                    :data="listDataPublic"
                                    :sort-by="['name']"
                                    @sort-change="changeTableSort1"
                                    border
                                >
                                    <!-- <div v-if="publicDoctorShare != 0"> -->
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
                                            prop="publicNursingServices"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="150"
                                            label="Non-medical"
                                            :formatter="formatNumber"
                                            prop="publicNonMedical"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="140"
                                            label="Total"
                                            :formatter="formatNumber"
                                            prop="publicFiftyTotal"
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
                                            prop="publicDoctorShare"
                                            :sortable="'custom'"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="150"
                                            label="Pooled (15%)"
                                            :formatter="formatNumber"
                                            prop="publicPooled"
                                        ></el-table-column>
                                        <el-table-column
                                            min-width="140"
                                            label="Total"
                                            :formatter="formatNumber"
                                            prop="publicPbsTotal"
                                        ></el-table-column>
                                    </el-table-column>
                                    <!-- </div> -->
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
                                    :data="listDataPrivate"
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
                                                prop="privateNursingServices"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="150"
                                                label="Non-medical"
                                                :formatter="formatNumber"
                                                prop="privateNonMedical"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="140"
                                                label="Total"
                                                :formatter="formatNumber"
                                                prop="privateFiftyTotal"
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
                                                prop="privateDoctorShare"
                                                :sortable="'custom'"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="150"
                                                label="Pooled (15%)"
                                                :formatter="formatNumber"
                                                prop="privatePooled"
                                            ></el-table-column>
                                            <el-table-column
                                                min-width="140"
                                                label="Total"
                                                :formatter="formatNumber"
                                                prop="privatePbsTotal"
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
import XLSX from "xlsx";
export default {
    data() {
        return {
            data: [],
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
            page1: 1,
            pageSize1: 10,
            sheet_data: [],
            sheet_data_private: []
        };
    },
    computed: {
        searchingPublic() {
            if (!this.search) {
                return this.data.filter(el => {
                    if (el.publicDoctorShare != 0) {
                        return el;
                    }
                });
            }
            this.page = 1;
            return this.data.filter(el => {
                if (el.publicDoctorShare > 0) {
                    return el.name
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                }
            });
        },
        listDataPublic() {
            this.total = this.searchingPublic.length;

            return this.searchingPublic.slice(
                this.pageSize * this.page - this.pageSize,
                this.pageSize * this.page
            );
        },
        searchingPrivate() {
            if (!this.search) {
                return this.data.filter(el => {
                    if (el.privateDoctorShare != 0) {
                        return el;
                    }
                });
            }
            this.page1 = 1;
            return this.data.filter(el => {
                if (el.privateDoctorShare > 0) {
                    return el.name
                        .toLowerCase()
                        .includes(this.search.toLowerCase());
                }
            });
        },
        listDataPrivate() {
            this.total1 = this.searchingPrivate.length;

            return this.searchingPrivate.slice(
                this.pageSize1 * this.page1 - this.pageSize1,
                this.pageSize1 * this.page1
            );
        }
    },
    methods: {
        formatNumber(row, column, cellValue, index) {
            return new Intl.NumberFormat().format(cellValue);
        },
        changes() {
            if (this.value != "") {
                //this.privateDoctors = [];
                //this.active = [];
                this.sumOfAll = [];
                this.nursing_services_total = 0;
                this.non_medical_total = 0;
                this.fifty_total_total = 0;
                this.doctors_share_total = 0;
                this.pooled_total = 0;
                this.pbs_total_total = 0;
                this.grand_total = 0;
                this.getSummary(this.value[0]);
            }
        },
        async getBatch() {
            await axios
                .get("get_batch")
                .then(response => {
                    response.data.push({ batch: "All" });
                    this.batch = response.data;
                    this.value[0] = response.data[0].batch;
                    this.first_batch = response.data[0].batch;
                    this.getSummary(this.value);
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
            } else {
                this.data = this.privateDoctors.sort(
                    (a, b) => a[fieldName] - b[fieldName]
                );
            }
        },
        async getSummary(batch) {
            await axios
                .get(`get_summary/${batch}`)
                .then(response => {
                    response.data.forEach(doctor => {
                        doctor.publicNursingServices = 0;
                        doctor.privateNursingServices = 0;

                        doctor.publicNonMedical = 0;
                        doctor.privateNonMedical = 0;

                        doctor.publicFiftyTotal = 0;
                        doctor.privateFiftyTotal = 0;

                        // doctor.doctors_share = 0;
                        doctor.publicPooled = 0;
                        doctor.privatePooled = 0;

                        doctor.publicPbsTotal = 0;
                        doctor.privatePbsTotal = 0;

                        doctor.privateDoctorShare = 0;
                        doctor.publicDoctorShare = 0;

                        doctor.record_type = "";

                        doctor.credit_records.forEach(patient => {
                            if (patient.pooled_record == null) {
                                doctor.pooled = 0;
                            }
                            if (patient["record_type"] == "private") {
                                if (patient.total != "") {
                                    doctor.privateDoctorShare += Number(
                                        patient.total
                                    );
                                    doctor.privatePbsTotal =
                                        doctor.privateDoctorShare;
                                } else doctor.privateDoctorShare += 0;
                            } else if (patient.total != "") {
                                doctor.publicDoctorShare += Number(
                                    patient.total
                                );
                                doctor.publicNursingServices += Number(
                                    patient.medical_fee
                                );
                                doctor.publicNonMedical += Number(
                                    patient.non_medical_fee
                                );
                                doctor.publicFiftyTotal =
                                    doctor.publicNursingServices +
                                    doctor.publicNonMedical;

                                if (patient.pooled_record != null) {
                                    var holder = JSON.parse(
                                        patient.pooled_record.full_time_doctors
                                    ).includes(doctor.id);
                                    if (holder) {
                                        doctor.publicPooled = Number(
                                            patient.pooled_record
                                                .full_time_individual_fee
                                        );
                                    }
                                }
                                doctor.publicPbsTotal =
                                    doctor.publicPooled +
                                    doctor.publicDoctorShare;
                            } else doctor.publicDoctorShare += 0;
                        });
                        if (doctor.pooled != 0) {
                            this.nursing_services_total +=
                                doctor.publicNursingServices;
                            this.non_medical_total += doctor.publicNonMedical;
                            this.fifty_total_total += doctor.publicFiftyTotal;
                            this.doctors_share_total += Number(
                                doctor.publicDoctorShare
                            );
                            this.pooled_total += Number(doctor.publicPooled);
                            this.pbs_total_total += Number(
                                doctor.publicPbsTotal
                            );
                            this.grand_total =
                                this.fifty_total_total + this.pbs_total_total;
                        }
                    });
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
        exportSummary($event) {
            var header = {
                A1: { t: "s", v: "SUMMARY OF DOCTOR'S PERFORMANCE BASE" },
                A2: { t: "s", v: "COVERED PERIOD:" },
                A3: { t: "s", v: "NAME OF PHYSICIAN" },
                B3: { t: "s", v: "50%" },
                B4: { t: "s", v: "NURSING SERVICES" },
                C4: { t: "s", v: "NONE-MEDICAL" },
                D4: { t: "s", v: "TOTAL" },
                E3: { t: "s", v: "PERFORMANCE BASED SHARING" },
                E4: { t: "s", v: "DOCTORS SHARE (35%)" },
                F4: { t: "s", v: "POOLED (15%)" },
                G4: { t: "s", v: "TOTAL" },
                H3: { t: "s", v: "SIGNATURE" },
                "!merges": [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 7 } },
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 7 } },
                    { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
                    { s: { r: 2, c: 1 }, e: { r: 2, c: 3 } },
                    { s: { r: 2, c: 4 }, e: { r: 2, c: 6 } },
                    { s: { r: 2, c: 7 }, e: { r: 3, c: 7 } }
                ],
                "!ref": "A1:H5"
            };
            var header1 = {
                A1: { t: "s", v: "SUMMARY OF DOCTOR'S PERFORMANCE BASE" },
                A2: { t: "s", v: "COVERED PERIOD:" },
                A3: { t: "s", v: "NAME OF PHYSICIAN" },
                B3: { t: "s", v: "50%" },
                B4: { t: "s", v: "NURSING SERVICES" },
                C4: { t: "s", v: "NONE-MEDICAL" },
                D4: { t: "s", v: "TOTAL" },
                E3: { t: "s", v: "PERFORMANCE BASED SHARING" },
                E4: { t: "s", v: "DOCTORS SHARE (35%)" },
                F4: { t: "s", v: "POOLED (15%)" },
                G4: { t: "s", v: "TOTAL" },
                H3: { t: "s", v: "SIGNATURE" },
                "!merges": [
                    { s: { r: 0, c: 0 }, e: { r: 0, c: 7 } },
                    { s: { r: 1, c: 0 }, e: { r: 1, c: 7 } },
                    { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
                    { s: { r: 2, c: 1 }, e: { r: 2, c: 3 } },
                    { s: { r: 2, c: 4 }, e: { r: 2, c: 6 } },
                    { s: { r: 2, c: 7 }, e: { r: 3, c: 7 } }
                ],
                "!ref": "A1:H5"
            };
            this.sheet_data = [];
            this.sheet_data.push(header);
            this.sheet_data_private = [];
            this.sheet_data_private.push(header1);
            this.proccessLoading($event, function(loading_response){
                if(loading_response == "done"){
                    this.active = [];
                    this.privateDoctors = [];
                    this.page = 1;
                    this.page1 = 1;
                    this.search = "";
                    this.pageSize = 1000;
                    this.pageSize1 = 1000;
                    try {
                        this.listDataPublic.forEach((data)=>{
                            this.active.push(data);
                        })
                        this.listDataPrivate.forEach((data)=>{
                            this.privateDoctors.push(data);
                        })
                        this.pageSize = 10;
                        this.pageSize1 = 10;
                    } catch (error) {
                        this.pageSize = 10;
                        this.pageSize1 = 10;
                    }
                    var row = 4;
                    var prow = 4;
                    this.active.forEach(physician => {
                        row += 1;
                        this.sheet_data[0]["A" + row] = { t: "s", v: physician.name };
                        this.sheet_data[0]["B" + row] = { t: "n", v: physician.publicNursingServices };
                        this.sheet_data[0]["C" + row] = { t: "n", v: physician.publicNonMedical };
                        this.sheet_data[0]["D" + row] = { t: "n", v: physician.publicFiftyTotal };
                        this.sheet_data[0]["E" + row] = { t: "n", v: physician.publicDoctorShare };
                        this.sheet_data[0]["F" + row] = { t: "n", v: physician.publicPooled };
                        this.sheet_data[0]["G" + row] = { t: "n", v: physician.publicPbsTotal };
                    });
                    this.privateDoctors.forEach(physician => {
                        prow += 1;
                        this.sheet_data_private[0]["A" + prow] = { t: "s", v: physician.name };
                        this.sheet_data_private[0]["B" + prow] = { t: "n", v: physician.privateNursingServices };
                        this.sheet_data_private[0]["C" + prow] = { t: "n", v: physician.privateNonMedical };
                        this.sheet_data_private[0]["D" + prow] = { t: "n", v: physician.privateFiftyTotal };
                        this.sheet_data_private[0]["E" + prow] = { t: "n", v: physician.privateDoctorShare };
                        this.sheet_data_private[0]["F" + prow] = { t: "n", v: physician.privatePooled };
                        this.sheet_data_private[0]["G" + prow] = { t: "n", v: physician.privatePbsTotal };
                    });
                    row += 2;
                    this.sheet_data[0]["A" + row] = { t: "s", v: "TOTAL" };
                    this.sheet_data[0]["B" + row] = { t: "n", v: this.nursing_services_total };
                    this.sheet_data[0]["C" + row] = { t: "n", v: this.non_medical_total };
                    this.sheet_data[0]["D" + row] = { t: "n", v: this.fifty_total_total };
                    this.sheet_data[0]["E" + row] = { t: "n", v: this.doctors_share_total };
                    this.sheet_data[0]["F" + row] = { t: "n", v: this.pooled_total };
                    this.sheet_data[0]["G" + row] = { t: "n", v: this.pbs_total_total };
                    row += 1;
                    this.sheet_data[0]["G" + row] = { t: "s", v: "GRAND TOTAL" };
                    this.sheet_data[0]["H" + row] = { t: "n", v: this.grand_total };
                    this.sheet_data[0]["!ref"] = "A1:H" + row;
                    this.sheet_data_private[0]["!ref"] = "A1:H" + prow;
                    var sheet_name;
                    if (
                        typeof this.value[0] !== "undefined" ||
                        this.value[0] == "All"
                    ) {
                        if (this.value[0] == "All") {
                            sheet_name = "All Record";
                        } else {
                            var month_name = [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sept",
                                "Oct",
                                "Nov",
                                "Dec"
                            ];
                            var d = this.value[0].trim().split("-");
                            var date_from =
                                month_name[parseInt(d[0][2] + d[0][3]) - 1] +
                                " " +
                                d[0][0] +
                                d[0][1] +
                                " " +
                                d[0][4] +
                                d[0][5] +
                                d[0][6] +
                                d[0][7];
                            var date_to =
                                month_name[parseInt(d[1][2] + d[1][3]) - 1] +
                                " " +
                                d[1][0] +
                                d[1][1] +
                                " " +
                                d[1][4] +
                                d[1][5] +
                                d[1][6] +
                                d[1][7];
                            sheet_name = date_from + " - " + date_to;
                            this.sheet_data[0].A2.v =
                                "COVERED PERIOD: " + sheet_name.toUpperCase();
                            this.sheet_data_private[0].A2.v =
                                "COVERED PERIOD: " + sheet_name.toUpperCase();
                        }
                        var sheet_data_object = {};
                        sheet_data_object[sheet_name] = this.sheet_data[0];
                        sheet_data_object["Private"] = this.sheet_data_private[0];
                        XLSX.writeFile(
                            {
                                SheetNames: [sheet_name, "Private"],
                                Sheets: sheet_data_object
                            },
                            "Summary_Export.xlsx"
                        );
                    } else {
                        this.$notify({
                            type: "warning",
                            title: "Export",
                            message: "Please select batch to proceed"
                        });
                    }
                }
            }.bind(this))
        },
        proccessLoading(event, cb){
            var btn = event.currentTarget.id;
            this.$refs[btn].loading = true;
            setTimeout(function () {
                cb('done');
                this.$refs[btn].loading = false;
            }.bind(this), 1000)
        },
    },
    mounted() {
        this.getBatch();
    }
};
</script>
