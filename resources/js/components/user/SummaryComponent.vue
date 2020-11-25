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
            <div class="col-5">
                <el-input
                    prefix-icon="el-icon-search"
                    v-model="search"
                    size="medium"
                    placeholder="Type to search"
                />
            </div>
        </div>

        <!-- <div class="row">
            <div class="col-7">
                <el-form>
                    <el-form-item label="Batch">
                        <el-autocomplete
                            class="inline-input"
                            placeholder="Please Input"
                            :trigger-on-focus="false"
                        ></el-autocomplete>
                    </el-form-item>
                </el-form>
            </div>
        </div> -->

        <!-- Card Begins Here -->
        <div class="card">
            <div class="card-body">

                <!-- Table -->
                <el-table :data="listData">
                    <el-table-column
                        width="360"
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
                            width="150"
                            label="Total"
                            prop="fifty_total"
                        ></el-table-column>
                    </el-table-column>
                    <el-table-column label="Performance Based Sharing">
                        <el-table-column
                            width="150"
                            label="Doctors Share (35%)"
                            prop="doctors_share"
                        ></el-table-column>
                        <el-table-column
                            width="150"
                            label="Pooled"
                            prop="pooled"
                        ></el-table-column>
                        <el-table-column
                            width="150"
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
            </div>
        </div>
        <!-- Card ends here -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            data: [],
            search: "",
            page: 1,
            pageSize: 10,
        }
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
                    data.name
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
        getSummary: function() {
            axios
                .get("get_summary")
                .then(response => {
                    response.data.forEach(doctor => {
                        doctor.nursing_services=0,
                        doctor.non_medical=0,
                        doctor.fifty_total=0,
                        doctor.doctors_share=0,
                        doctor.pooled=0;
                        doctor.pbs_total=0;
                        doctor.credit_records.forEach(patient => {
                           doctor.doctors_share+=Number(patient.pivot.professional_fee);
                            doctor.pooled+=Number(patient.pivot.pooled_fee);
                            doctor.pbs_total=Number(doctor.doctors_share+doctor.pooled);
                            doctor.nursing_services+=Number(patient.medical_fee);
                            doctor.non_medical+=Number(patient.non_medical_fee);
                            doctor.fifty_total=Number(doctor.nursing_services+doctor.non_medical);
                        })
                    });

                    this.data = response.data;
                })
                .catch(function(error) {});
        },
        handleCurrentChange(val) {
            this.page = val;
        },
    },
    mounted() {
        this.getSummary();
    }
};
</script>
