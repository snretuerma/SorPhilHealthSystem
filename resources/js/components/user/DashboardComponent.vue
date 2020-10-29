<template>
    <div>
        <!-- Header -->
        <div class="row">
            <div class="col-sm-12">
                <h2 class="font-weight-bold"><i class="fa fa-tachometer-alt"></i>&nbsp;&nbsp;User Dashboard</h2>
            </div>
        </div>
        <hr />
        <!-- End Header -->

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fa fa-funnel-dollar fa-lg"></i><span class="text-wrap font-weight-bold">&nbsp;&nbsp;Total Pooled Professional Fee</span>
                        <hr />
                        <h1><i class="fa fa-ruble-sign fa-lg"></i><span class="text-wrap">&nbsp;&nbsp;1,234,567,891</span></h1>
                    </div>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fa fa-calendar-alt fa-lg"></i><span class="text-wrap font-weight-bold">&nbsp;&nbsp;PF per month</span>
                        <hr />
                        <canvas id="lineChart" width="400" height="91"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">

                <div class="card">
                    <div class="card-body">
                        <i class="fa fa-file-medical-alt fa-lg"></i><span class="text-wrap font-weight-bold">&nbsp;&nbsp;Recent Medical Records</span>
                        <hr />
                        <!-- Table -->
                            <el-table v-loading="loading" :data="data">
                                <el-table-column width="120" label="Admission date" prop="admission_date"></el-table-column>
                                <el-table-column width="120" label="Discharge date" prop="discharge_date"></el-table-column>
                                <el-table-column width="150" label="Final Diagnosis" prop="final_diagnosis"></el-table-column>
                                <el-table-column width="120" label="Record Type" prop="record_type"></el-table-column>
                                <el-table-column width="120" label="Total Fee" prop="total_fee"></el-table-column>
                            </el-table>
                        <!--End table -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <i class="fa fa-hand-holding-usd fa-lg"></i><span class="text-wrap font-weight-bold">&nbsp;&nbsp;Recent Contributions</span>
                        <hr />
                        <!-- Table -->
                            <el-table v-loading="loading" :data="data1">
                                <el-table-column width="200" label="Name" prop="name"></el-table-column>
                                <el-table-column width="120" label="Type" prop="record_type"></el-table-column>
                                <el-table-column width="120" label="Contribution" prop="contribution"></el-table-column>
                                <el-table-column width="120" label="Credit" prop="credit"></el-table-column>
                                <el-table-column width="80" label="Status" prop="status"></el-table-column>
                            </el-table>
                        <!--End table -->
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="pieChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <canvas id="barChart" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
        data: [],
        data1: [],
        loading: true
        }
    },
    methods: {
        getRecentMedicalRecord: function () {
			axios
				.get("user/recentMedicalRecord_get")
				.then((response) => {
						this.data = response.data;
						this.loading = false;
				})
				.catch(function (error) {});
        },
        getRecentContribution: function () {
			axios
				.get("user/recentContribution_get")
				.then((response) => {
                    response.data.forEach((element) => {
                    if (element.name_suffix == undefined) {
                                    element.name_suffix = "";
                                }
                        element.name = this.buildName(element.first_name, element.middle_name,element.last_name,element.name_suffix);
                    })

                        this.data1 = response.data;
						this.loading = false;
				})
				.catch(function (error) {});
        },
        buildName: function (first_name, middle_name, last_name, suffix) {
			return (last_name + " " + suffix + ", " + first_name + " " + middle_name.slice(0, 1) + ".").trim();
		},
        pieChart() {
            var ctx = document.getElementById("pieChart");
            var myChart = new Chart(ctx, {
                type: "radar",
                data: {
                    labels: [
                        "Acute cholecystitis",
                        "Food poisoning",
                        "Flu",
                        "Heart failure",
                        "HIV",
                        "Lung cancer",
                        "Multiple myeloma",
                        "2019-nCoV",
                        "Ebola",
                        "Hantavirus",
                        "Hepatitis A",
                        "Rabies",
                        "West Nile Virus",
                        "Zika",
                        "Measles",
                        "CRE",
                        "Enterovirus D68",
                        "MRSA",
                        "Shigellosis" //19
                    ],
                    datasets: [
                        {
                            label: "# of Votes",
                            data: [2,2,12,10,23,4,7,100,9,51,23,2,1,23,23,12,30,18,10],
                            backgroundColor: [
                                "rgba(207, 146, 57, 0.2)",
                                "rgba(104, 189, 111, 0.2)",
                                "rgba(25, 211, 191, 0.2)",
                                "rgba(141, 138, 113, 0.2)",
                                "rgba(230, 109, 136, 0.2)",
                                "rgba(255, 0, 0, 0.2)",
                                "rgba(255, 165, 0, 0.2)",
                                "rgba(255, 255, 0, 0.2)",
                                "rgba(0, 128, 0, 0.2)",
                                "rgba(0, 0, 255, 0.2)",
                                "rgba(75, 0, 130, 0.2)",
                                "rgba(238, 130, 238, 0.2)",
                                "rgba(135, 117, 168, 0.2)",
                                "rgba(109, 244, 166, 0.2)",
                                "rgba(195, 254, 56, 0.2)",
                                "rgba(225, 134, 99, 0.2)",
                                "rgba(236, 157, 127, 0.2)",
                                "rgba(47, 203, 231, 0.2)",
                                "rgba(255, 99, 132, 0.2)",

                            ],
                            borderColor: [
                                "rgba(104, 189, 111, 2)",
                                 "rgba(25, 211, 191, 2)",
                                 "rgba(207, 146, 57, 2)",
                                "rgba(141, 138, 113, 2)",
                                "rgba(230, 109, 136, 2)",
                                    "rgba(255, 0, 0, 2)",
                                  "rgba(255, 165, 0, 2)",
                                  "rgba(255, 255, 0, 2)",
                                    "rgba(0, 128, 0, 2)",
                                    "rgba(0, 0, 255, 2)",
                                   "rgba(75, 0, 130, 2)",
                                "rgba(238, 130, 238, 2)",
                                "rgba(135, 117, 168, 2)",
                                "rgba(109, 244, 166, 2)",
                                 "rgba(195, 254, 56, 2)",
                                 "rgba(225, 134, 99, 2)",
                                "rgba(236, 157, 127, 2)",
                                 "rgba(47, 203, 231, 2)",
                                 "rgba(255, 99, 132, 2)",
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ]
                    }
                }
            });
        },
        lineChart() {
            var ctx = document.getElementById("lineChart");
            var myChart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: [
                       "January",
                       "February",
                       "March"
                    ],
                    datasets: [
                        {
                            label: "Computed PF",
                            data: [200,300,500],
                            backgroundColor: [
                               "rgba(207, 146, 57, 0.2)",
                                "rgba(104, 189, 111, 0.2)",
                                "rgba(25, 211, 191, 0.2)",
                            ],
                            borderColor: [
                                "rgba(104, 189, 111, 2)",
                                 "rgba(25, 211, 191, 2)",
                                 "rgba(207, 146, 57, 2)",
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ]
                    }
                }
            });
        },
        barChart() {
            var ctx = document.getElementById("barChart");
            var myChart = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: [
                       "Acute cholecystitis",
                        "Food poisoning",
                        "Flu",
                        "Heart failure",
                        "HIV",
                        "Lung cancer",
                        "Multiple myeloma",
                        "2019-nCoV",
                        "Ebola",
                        "Hantavirus",
                        "Hepatitis A",
                        "Rabies",
                        "West Nile Virus",
                        "Zika",
                        "Measles",
                        "CRE",
                        "Enterovirus D68",
                        "MRSA",
                        "Shigellosis" //19
                    ],
                    datasets: [
                        {
                            label: "# of Votes",
                            data: [2,2,12,10,23,4,7,100,9,51,23,2,1,23,23,12,30,18,10],
                            backgroundColor: [
                               "rgba(207, 146, 57, 0.2)",
                                "rgba(104, 189, 111, 0.2)",
                                "rgba(25, 211, 191, 0.2)",
                                "rgba(141, 138, 113, 0.2)",
                                "rgba(230, 109, 136, 0.2)",
                                "rgba(255, 0, 0, 0.2)",
                                "rgba(255, 165, 0, 0.2)",
                                "rgba(255, 255, 0, 0.2)",
                                "rgba(0, 128, 0, 0.2)",
                                "rgba(0, 0, 255, 0.2)",
                                "rgba(75, 0, 130, 0.2)",
                                "rgba(238, 130, 238, 0.2)",
                                "rgba(135, 117, 168, 0.2)",
                                "rgba(109, 244, 166, 0.2)",
                                "rgba(195, 254, 56, 0.2)",
                                "rgba(225, 134, 99, 0.2)",
                                "rgba(236, 157, 127, 0.2)",
                                "rgba(47, 203, 231, 0.2)",
                                "rgba(255, 99, 132, 0.2)",
                            ],
                            borderColor: [
                                "rgba(104, 189, 111, 2)",
                                 "rgba(25, 211, 191, 2)",
                                 "rgba(207, 146, 57, 2)",
                                "rgba(141, 138, 113, 2)",
                                "rgba(230, 109, 136, 2)",
                                    "rgba(255, 0, 0, 2)",
                                  "rgba(255, 165, 0, 2)",
                                  "rgba(255, 255, 0, 2)",
                                    "rgba(0, 128, 0, 2)",
                                    "rgba(0, 0, 255, 2)",
                                   "rgba(75, 0, 130, 2)",
                                "rgba(238, 130, 238, 2)",
                                "rgba(135, 117, 168, 2)",
                                "rgba(109, 244, 166, 2)",
                                 "rgba(195, 254, 56, 2)",
                                 "rgba(225, 134, 99, 2)",
                                "rgba(236, 157, 127, 2)",
                                 "rgba(47, 203, 231, 2)",
                                 "rgba(255, 99, 132, 2)",
                            ],
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        yAxes: [
                            {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        ]
                    }
                }
            });
        }
    },
    mounted() {
        this.pieChart();
        this.lineChart();
        this.barChart();
        this.getRecentMedicalRecord();
        this.getRecentContribution();
    }
};
</script>
