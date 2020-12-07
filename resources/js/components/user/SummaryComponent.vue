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
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    placeholder="Type to search"
                />
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <el-form>
                    <el-form-item>
                        <el-autocomplete
                            prefix-icon="el-icon-search"
                            class="inline-input"
                            placeholder="Batch"
                            value=""
                            :trigger-on-focus="false"
                        ></el-autocomplete>
                    </el-form-item>
                </el-form>
            </div>
        </div>

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">
                <!-- Table -->
                <el-table :data="listData" @sort-change="changeTableSort">
                    <el-table-column
                        width="350"
                        label="Name of Physician"
                        prop="name"
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
                            label="Pooled"
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
                <el-table :data="inactive">
                    <el-table-column
                        label="Physicians not included for performance based sharing"
                    >
                        <el-table-column
                            width="350"
                            label="Name of Physician"
                            prop="name"
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
                            ></el-table-column>
                        </el-table-column>
                        <el-table-column label="Performance Based Sharing">
                            <el-table-column
                                width="170"
                                label="Doctors Share (35%)"
                                prop="doctors_share"
                                sortable
                            ></el-table-column>
                            <el-table-column
                                width="150"
                                label="Pooled"
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
                <el-table :data="total">
                    <el-table-column width="350"></el-table-column>
                    <el-table-column
                        label="Nursing Services Total"
                        width="150"
                        prop="nursing_services_total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        prop="non_medical_total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        prop="fifty_total_total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        prop="doctors_share_total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        prop="pooled_total"
                    ></el-table-column>
                    <el-table-column
                        width="150"
                        prop="pbs_total_total"
                    ></el-table-column>
                </el-table>
                <!-- End table -->
            </div>
        </div>
        <!-- Card ends here -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            active:[],
            inactive: [],
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
        changeTableSort(column) {
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
        getSummary: function() {
            axios
                .get("get_summary")
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

                                if (doctor.is_parttime == 0 && JSON.parse(doctor.credit_records[0].pooled_record.full_time_doctors).includes(doctor.id)) {
                                    doctor.pooled =
                                        Number(
                                            patient.pooled_record
                                                .full_time_individual_fee
                                        );
                                } else {
                                    doctor.pooled =
                                        Number(
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
                            // console.log(patient.pooled_record.part_time_individual_fee);
                        });
                        doctor.doctors_share = doctor.doctors_share.toFixed(4);
                        doctor.pooled = doctor.pooled.toFixed(4);
                        doctor.pbs_total = doctor.pbs_total.toFixed(4);

                        if(doctor.is_active == true && doctor.pooled!=0) this.active.push(doctor);
                        else if(doctor.is_active == false && doctor.pooled!=0) this.inactive.push(doctor);
                    });

                    this.data = response.data;
                    // console.log(this.data);
                })
                .catch(function(error) {});
        },
        handleCurrentChange(val) {
            this.page = val;
        }
    },
    mounted() {
        this.getSummary();
    }
};
</script>
