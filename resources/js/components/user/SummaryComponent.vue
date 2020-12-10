<template>
    <div>
        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                    <i class="fa fa-list-alt"></i>&nbsp;&nbsp;Summary of
                    Doctor's Performance Base
                </span>
            </div>
        </div>
        <!-- End Header -->

        <!-- Search Box -->
        <div class="row" style="margin-bottom: 10px">
            <div class="col-xl-7 col-lg-7 col-md-6 col-sm-12">
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
                            @change="changes">
                            <el-option
                                v-for="item in batch"
                                :key="item.batch"
                                :label="item.label"
                                :value="item.batch"
                                >
                            {{item.batch}}</el-option>
                        </el-select>
                    </el-form-item>
                </el-form>
            </div>
        </div>

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table
                    :data="listData"
                    :sort-by = "['name']"
                    @sort-change="changeTableSort1"
                    border
                >
                    <el-table-column
                        width="350"
                        label="Name of Physician"
                        prop="name"
                        :sortable="'custom'"
                    ></el-table-column>
                    <el-table-column label="50%">
                        <el-table-column
                            width="150"
                            label="Nursing Services"
                            prop="nursing_services"
                        ></el-table-column>
                        <el-table-column
                            width="150"
                            label="Non-medical"
                            prop="non_medical"
                        ></el-table-column>
                        <el-table-column
                            width="140"
                            label="Total"
                            prop="fifty_total"
                            :sortable="'custom'"
                        ></el-table-column>
                    </el-table-column>
                    <el-table-column label="Performance Based Sharing">
                        <el-table-column
                            width="170"
                            label="Doctors Share (35%)"
                            prop="doctors_share"
                            :sortable="'custom'"
                        ></el-table-column>
                        <el-table-column
                            width="150"
                            label="Pooled (15%)"
                            prop="pooled"
                        ></el-table-column>
                        <el-table-column
                            width="140"
                            label="Total"
                            prop="pbs_total"
                        ></el-table-column>
                    </el-table-column>
                </el-table>
                <!-- End table -->
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
                <br />
                <el-table :data="inactive" @sort-change="changeTableSort2" border>
                    <el-table-column
                        label="Physicians not included for performance based sharing"
                    >
                        <el-table-column
                            width="350"
                            label="Name of Physician"
                            prop="name"
                            sortable
                        ></el-table-column>
                        <el-table-column label="50%">
                            <el-table-column
                                width="150"
                                label="Nursing Services"
                                prop="nursing_services"
                            ></el-table-column>
                            <el-table-column
                                width="150"
                                label="Non-medical"
                                prop="non_medical"
                            ></el-table-column>
                            <el-table-column
                                width="140"
                                label="Total"
                                prop="fifty_total"
                                :sortable="'custom'"
                            ></el-table-column>
                        </el-table-column>
                        <el-table-column label="Performance Based Sharing">
                            <el-table-column
                                width="170"
                                label="Doctors Share (35%)"
                                prop="doctors_share"
                                :sortable="'custom'"
                            ></el-table-column>
                            <el-table-column
                                width="150"
                                label="Pooled (15%)"
                                prop="pooled"
                            ></el-table-column>
                            <el-table-column
                                width="140"
                                label="Total"
                                prop="pbs_total"
                            ></el-table-column>
                        </el-table-column>
                    </el-table-column>
                </el-table>
                <!-- End table -->
                <br />
                <el-table
                    :data="sumOfAll"
                    border
                    :row-class-name="tableRowClassName"
                >
                    <el-table-column
                        label="Nursing Services Total"
                        width="170"
                        prop="nursing_services_total"
                    ></el-table-column>
                    <el-table-column
                        label="Non-medical Total"
                        width="180"
                        prop="non_medical_total"
                    ></el-table-column>
                    <el-table-column
                        label="Total"
                        width="180"
                        prop="fifty_total_total"
                    ></el-table-column>
                    <el-table-column
                        label="Doctor Share Total"
                        width="180"
                        prop="doctors_share_total"
                    ></el-table-column>
                    <el-table-column
                        label="Pooled Total"
                        width="180"
                        prop="pooled_total"
                    ></el-table-column>
                    <el-table-column
                        label="Total"
                        width="180"
                        prop="pbs_total_total"
                    ></el-table-column>
                    <el-table-column
                        label="Grand Total"
                        width="180"
                        prop="grand_total"
                    >
                    </el-table-column>
                </el-table>
                <!-- End table -->
                </br>
                <br />
                <el-table :data="privateDoctors" @sort-change="changeTableSort3" border >
                    <el-table-column
                        label="Private Doctor Records"
                    >
                        <el-table-column
                            width="350"
                            label="Name of Physician"
                            prop="name"
                            sortable
                        ></el-table-column>
                        <el-table-column label="50%">
                            <el-table-column
                                width="150"
                                label="Nursing Services"
                                prop="nursing_services"
                            ></el-table-column>
                            <el-table-column
                                width="150"
                                label="Non-medical"
                                prop="non_medical"
                            ></el-table-column>
                            <el-table-column
                                width="140"
                                label="Total"
                                prop="fifty_total"
                                :sortable="'custom'"
                            ></el-table-column>
                        </el-table-column>
                        <el-table-column label="Performance Based Sharing">
                            <el-table-column
                                width="170"
                                label="Doctors Share (35%)"
                                prop="doctors_share"
                                :sortable="'custom'"
                            ></el-table-column>
                            <el-table-column
                                width="150"
                                label="Pooled (15%)"
                                prop="pooled"
                            ></el-table-column>
                            <el-table-column
                                width="140"
                                label="Total"
                                prop="pbs_total"
                            ></el-table-column>
                        </el-table-column>
                    </el-table-column>
                </el-table>
                <!-- End table -->
            </div>
        </div>
        <!-- Card ends here -->
    </div>
</template>
<style>
.el-table .success-row {
    background: #fce9e9;
}
</style>

<script>
export default {
    data() {
        return {
            active: [],
            inactive: [],
            sumOfAll: [],
            batch:[],
            value:[],
            privateDoctors:[],
            nursing_services_total: 0,
            non_medical_total: 0,
            fifty_total_total: 0,
            doctors_share_total: 0,
            pooled_total: 0,
            pbs_total_total: 0,
            grand_total: 0,
            search: "",
            page: 1,
            pageSize: 10
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
        changes(){
            if(this.value != '') {
                this.getRecords(this.value);
            }
        },
        getBatch(){
             axios
                .get("get_batch")
                .then(response => {
                    response.data.push({batch:'All'});
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
            console.log(column);
            //Get the field name and sort type
            var fieldName = column.prop;
            var sortingType = column.order;

            //Sort in descending order
            if (sortingType == "descending") {
                this.data = this.active.sort(
                    (a, b) => b[fieldName] - a[fieldName]
                );
            }
            //Sort in ascending order
            else {
                this.data = this.active.sort(
                    (a, b) => a[fieldName] - b[fieldName]
                );
            }
        },
        changeTableSort2(column) {

            //Get the field name and sort type
            var fieldName = column.prop;
            var sortingType = column.order;

            //Sort in descending order
            if (sortingType == "descending") {
                this.data = this.inactive.sort(
                    (a, b) => b[fieldName] - a[fieldName]
                );
            }
            //Sort in ascending order
            else {
                this.data = this.inactive.sort(
                    (a, b) => a[fieldName] - b[fieldName]
                );
            }
        },
        changeTableSort3(column) {

            //Get the field name and sort type
            var fieldName = column.prop;
            var sortingType = column.order;

            //Sort in descending order
            if (sortingType == "descending") {
                this.data = this.privateDoctors.sort(
                    (a, b) => b[fieldName] - a[fieldName]
                );
            }
            //Sort in ascending order
            else {
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
                        });

                        doctor.doctors_share = doctor.doctors_share.toFixed(4);
                        doctor.pooled = doctor.pooled.toFixed(4);
                        doctor.pbs_total = doctor.pbs_total.toFixed(4);
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
                                this.fifty_total_total +
                                this.pbs_total_total;
                        }
                        if (doctor.is_active == true && doctor.pooled != 0)
                            this.active.push(doctor);
                        else if (
                            doctor.is_active == false &&
                            doctor.pooled != 0
                        )
                            this.inactive.push(doctor);
                        else  this.privateDoctors.push(doctor);
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
        }
    },
    mounted() {
        this.getBatch();
    }
};
</script>
