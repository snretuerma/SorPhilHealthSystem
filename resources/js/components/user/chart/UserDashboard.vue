<template>
    <div>

        <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-tachometer-alt"></i>&nbsp;&nbsp;Dashboard
                </span>
            </div>
        </div>
        <!-- End Header -->

        <div>
            <el-row :gutter="12">
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-funnel-dollar fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Total computed PF -
                            </span>
                            <el-date-picker
                                v-model="filterTotalComputedPF"
                                @change="
                                    updateDataFunction(
                                        $event,
                                        'Total',
                                        'computedPF'
                                    )
                                "
                                :clearable="false"
                                type="month"
                                placeholder="Pick a month"
                                size="mini"
                                style="float: right; width:110px;"
                            >
                            </el-date-picker>
                            <a href="user/record">
                                <el-button
                                    style="padding: 6px 5px 6px 5px;font-size:12px;"
                                    type="text"
                                    >View more</el-button
                                >
                            </a>
                        </div>
                        <div
                            class="text item"
                            style="font-size:20px;margin-bottom:0px;color:grey;"
                        >
                            <strong>
                                {{ totalComputedPF }}
                            </strong>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-file-invoice-dollar fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Total pooled PF -
                            </span>
                            <el-date-picker
                                v-model="filterTotalPooledFee"
                                @change="
                                    updateDataFunction(
                                        $event,
                                        'Total',
                                        'pooledFee'
                                    )
                                "
                                :clearable="false"
                                type="month"
                                placeholder="Pick a month"
                                size="mini"
                                style="float: right; width:110px;"
                            >
                            </el-date-picker>
                            <a href="user/record">
                                <el-button
                                    style="padding: 6px 5px 6px 5px;font-size:12px;"
                                    type="text"
                                    >View more</el-button
                                >
                            </a>
                        </div>
                        <div
                            class="text item"
                            style="font-size:20px;margin-bottom:0px;color:grey;"
                        >
                            <strong>
                                {{ totalPooledFee }}
                            </strong>
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </div>
        <br />
        <div>
            <el-row :gutter="12">
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-file-medical-alt fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Recent Medical Records</span
                            >
                        </div>
                        <div>
                            <!-- Table -->
                            <el-table v-loading="loading" :data="data">
                                <el-table-column
                                    width="200"
                                    label="Patient Name"
                                    prop="name">
                                </el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Admission date"
                                    prop="admission_date"
                                ></el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Discharge date"
                                    prop="discharge_date"
                                ></el-table-column>
                                <el-table-column
                                    width="150"
                                    label="Final Diagnosis"
                                    prop="final_diagnosis"
                                ></el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Record Type"
                                    prop="record_type"
                                ></el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Total Fee"
                                    prop="total_fee"
                                ></el-table-column>
                            </el-table>
                            <!--End table -->
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-hand-holding-usd fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Recent Contributions</span
                            >
                        </div>
                        <div>
                            <!-- Table -->
                            <el-table v-loading="loading" :data="data1">
                                <el-table-column
                                    width="200"
                                    label="Staff Name"
                                    prop="name"
                                ></el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Type"
                                    prop="record_type"
                                ></el-table-column>
                                <el-table-column
                                    width="170"
                                    label="Contribution"
                                    prop="contribution"
                                ></el-table-column>
                                <el-table-column
                                    width="120"
                                    label="Credit"
                                    prop="credit"
                                ></el-table-column>
                                <el-table-column
                                    width="80"
                                    label="Status"
                                    prop="status"
                                ></el-table-column>
                            </el-table>
                            <!--End table -->
                        </div>
                    </el-card>
                </el-col>
            </el-row>
        </div>
        <br />
        <div>
            <el-row :gutter="12">
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-bacteria fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Most Common Illness</span
                            >
                        </div>
                        <el-button
                            size="mini"
                            @click="
                                updateDataFunction(
                                    $event,
                                    'commonDisease',
                                    'byyesterday'
                                )
                            "
                            >Yesterday</el-button
                        >
                        <el-button
                            size="mini"
                            @click="
                                updateDataFunction(
                                    $event,
                                    'commonDisease',
                                    'bylastweek'
                                )
                            "
                            >Last week</el-button
                        >
                        <el-date-picker
                            v-model="filterCommonDisease"
                            @change="
                                updateDataFunction(
                                    $event,
                                    'commonDisease',
                                    'bymonth'
                                )
                            "
                            :clearable="false"
                            type="month"
                            placeholder="Pick a month"
                            size="mini"
                            style="float: right; width:110px;"
                        >
                        </el-date-picker>
                        <canvas
                            id="chartCommonDisease"
                            width="400"
                            height="300"
                        ></canvas>
                    </el-card>
                </el-col>
                <el-col :span="12">
                    <el-card shadow="always">
                        <div slot="header" class="clearfix">
                            <i class="fa fa-calendar-alt fa-2x"></i>
                            <span style="font-size:1rem;"
                                >&nbsp;&nbsp;Received Professional Fee
                                Comparison per Month</span
                            >
                        </div>
                        <el-date-picker
                            v-model="filterReceivePF"
                            @change="
                                updateDataFunction($event, 'receivePF', '')
                            "
                            :clearable="false"
                            type="year"
                            placeholder="Pick a year"
                            size="mini"
                            style="float: right; width:110px"
                        >
                        </el-date-picker>
                        <canvas
                            id="chartReceivePF"
                            width="400"
                            height="300"
                        ></canvas>
                    </el-card>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>
import _ from "lodash";
var array = require("lodash/array");

export default {
    data() {
        return {
            filterCommonDisease: "",
            filterReceivePF: "",
            filterTotalComputedPF: "",
            filterTotalPooledFee: "",
            CommonDisease: [],
            tempCommonDisease: [],
            medicalData: [],
            yesterdayDisease: [],
            lastweekDisease: [],
            selectedByMonthDisease: [],
            chartDataCommonDisease: [],
            chartDataReceivePF: [],
            totalComputedPF: "",
            totalPooledFee: "",
            monjan: [],
            monfeb: [],
            monmar: [],
            monapr: [],
            monmay: [],
            monjune: [],
            monjuly: [],
            monaug: [],
            monsept: [],
            monoct: [],
            monnov: [],
            mondec: [],
            chart: [],
            chartRePF: [],
            loading: false,
            data: [],
            data1: []
        };
    },
    created() {},

    beforeMount() {},

    mounted() {
        var today = new Date();
        var month = today.getMonth() + 1;
        var year = today.getFullYear();
        this.filterCommonDisease = new Date(year + "-" + month);
        this.filterReceivePF = new Date(year + "-" + month);
        this.filterTotalComputedPF = new Date(year + "-" + month);
        this.filterTotalPooledFee = new Date(year + "-" + month);
        this.getMedicalData();
        this.getRecentMedicalRecord();
        this.getRecentContribution();
    },
    methods: {
        format_number(num) {
            var num_parts = num.toString().split(".");
            num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return num_parts.join(".");
        },
        updateDataFunction(selecteddate, updatefor, filterby) {
            var date = new Date(selecteddate);
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            var day = date.getDate();
            var _this = this;
            if (month < 10) {
                month = "0" + month;
            }
            if (day < 10) {
                day = "0" + day;
            }
            switch (updatefor) {
                case "Total":
                    var totalComputedPF = 0;
                    var totalPooledFee = 0;
                    if (filterby == "computedPF" || filterby == "pooledFee") {
                        _this.medicalData.forEach(element => {
                            var admission_month = element.admission_date;
                            var admission_month_split = admission_month.split(
                                "-"
                            );
                            if (
                                admission_month_split[0] +
                                    "-" +
                                    admission_month_split[1] ==
                                    year + "-" + month &&
                                typeof element.contributions !== "undefined"
                            ) {
                                if (filterby == "pooledFee") {
                                    totalPooledFee += Number(
                                        element.pooled_fee
                                    );
                                }
                                if (filterby == "computedPF") {
                                    element.contributions.forEach(el => {
                                        if (el.status == "paid") {
                                            totalComputedPF += Number(
                                                el.credit
                                            );
                                        }
                                    });
                                }
                            }
                        });
                        if (filterby == "computedPF") {
                            _this.totalComputedPF = "";
                            _this.totalComputedPF =
                                "₱ " +
                                _this.format_number(totalComputedPF.toFixed(4));
                        }
                        if (filterby == "pooledFee") {
                            _this.totalPooledFee = "";
                            _this.totalPooledFee =
                                "₱ " +
                                _this.format_number(totalPooledFee.toFixed(4));
                        }
                    }
                    break;
                case "commonDisease":
                    if (filterby == "bymonth") {
                        this.chartDataCommonDisease = [];
                        this.medicalData.forEach(element => {
                            var admission_month = element.admission_date;
                            var admission_month_split = admission_month.split(
                                "-"
                            );
                            if (
                                year + "-" + month ==
                                admission_month_split[0] +
                                    "-" +
                                    admission_month_split[1]
                            ) {
                                this.chartDataCommonDisease.push(
                                    element.final_diagnosis
                                );
                            }
                        });
                        this.chart.destroy();
                        this.chartCommonDisease();
                    } else if (filterby == "byyesterday") {
                        this.chartDataCommonDisease = [];
                        this.chartDataCommonDisease = this.yesterdayDisease;
                        this.chart.destroy();
                        this.chartCommonDisease();
                    } else if (filterby == "bylastweek") {
                        this.chartDataCommonDisease = [];
                        this.chartDataCommonDisease = this.lastweekDisease;
                        this.chart.destroy();
                        this.chartCommonDisease();
                    }
                    break;
                case "receivePF":
                    _this.monjan = [];
                    _this.monfeb = [];
                    _this.monmar = [];
                    _this.monapr = [];
                    _this.monmay = [];
                    _this.monjune = [];
                    _this.monjuly = [];
                    _this.monaug = [];
                    _this.monsept = [];
                    _this.monoct = [];
                    _this.monnov = [];
                    _this.mondec = [];
                    _this.medicalData.forEach(element => {
                        var admission_month = element.admission_date;
                        var admission_month_split = admission_month.split("-");
                        if (
                            admission_month_split[0] == year &&
                            typeof element.contributions !== "undefined"
                        ) {
                            for (let index = 1; index < 13; index++) {
                                if (index < 10) {
                                    index = "0" + index;
                                }
                                element.contributions.forEach(el => {
                                    var ad_month = element.admission_date;
                                    var ad_month_split = ad_month.split("-");
                                    if (
                                        el.status == "paid" &&
                                        ad_month_split[1] == index
                                    ) {
                                        if (index == "01") {
                                            _this.monjan.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "02") {
                                            _this.monfeb.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "03") {
                                            _this.monmar.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "04") {
                                            _this.monapr.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "05") {
                                            _this.monmay.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "06") {
                                            _this.monjune.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "07") {
                                            _this.monjuly.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "08") {
                                            _this.monaug.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "09") {
                                            _this.monsept.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "10") {
                                            _this.monoct.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "11") {
                                            _this.monnov.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "12") {
                                            _this.mondec.push(
                                                Number(el.credit)
                                            );
                                        }
                                    }
                                });
                            }
                        }
                    });
                    _this.chartDataReceivePF = [];
                    _this.chartDataReceivePF.push(
                        Number(_.sum(_this.monjan).toFixed(4)),
                        Number(_.sum(_this.monfeb).toFixed(4)),
                        Number(_.sum(_this.monmar).toFixed(4)),
                        Number(_.sum(_this.monapr).toFixed(4)),
                        Number(_.sum(_this.monmay).toFixed(4)),
                        Number(_.sum(_this.monjune).toFixed(4)),
                        Number(_.sum(_this.monjuly).toFixed(4)),
                        Number(_.sum(_this.monaug).toFixed(4)),
                        Number(_.sum(_this.monsept).toFixed(4)),
                        Number(_.sum(_this.monoct).toFixed(4)),
                        Number(_.sum(_this.monnov).toFixed(4)),
                        Number(_.sum(_this.mondec).toFixed(4))
                    );
                    _this.chartRePF.destroy();
                    _this.chartReceivePF();
            }
        },
        getMedicalData() {
            var _this = this;
            axios.get("user/get_common_disease").then(function(response) {
                var date = new Date();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                var day = date.getDate();
                var yesterday = new Date(
                    Date.now() -
                        1 * 86400000 -
                        new Date().getTimezoneOffset() * 60000
                )
                    .toISOString()
                    .split("T")[0];
                if (month < 10) {
                    month = "0" + month;
                }
                if (day < 10) {
                    day = "0" + day;
                }
                if (response.status > 199 && response.status < 203) {
                    _this.medicalData = response.data;
                    response.data.forEach(element => {
                        _this.CommonDisease.push(element.final_diagnosis);
                        if (element.admission_date == yesterday) {
                            _this.yesterdayDisease.push(
                                element.final_diagnosis
                            );
                        }
                        var admission_month = element.admission_date;
                        var admission_month_split = admission_month.split("-");
                        if (
                            year + "-" + month ==
                            admission_month_split[0] +
                                "-" +
                                admission_month_split[1]
                        ) {
                            _this.selectedByMonthDisease.push(
                                element.final_diagnosis
                            );
                        }
                        if (
                            admission_month_split[0] == year &&
                            typeof element.contributions !== "undefined"
                        ) {
                            for (let index = 1; index < 13; index++) {
                                if (index < 10) {
                                    index = "0" + index;
                                }
                                element.contributions.forEach(el => {
                                    var ad_month = element.admission_date;
                                    var ad_month_split = ad_month.split("-");
                                    if (
                                        el.status == "paid" &&
                                        ad_month_split[1] == index
                                    ) {
                                        if (index == "01") {
                                            _this.monjan.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "02") {
                                            _this.monfeb.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "03") {
                                            _this.monmar.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "04") {
                                            _this.monapr.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "05") {
                                            _this.monmay.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "06") {
                                            _this.monjune.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "07") {
                                            _this.monjuly.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "08") {
                                            _this.monaug.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "09") {
                                            _this.monsept.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "10") {
                                            _this.monoct.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "11") {
                                            _this.monnov.push(
                                                Number(el.credit)
                                            );
                                        } else if (index == "12") {
                                            _this.mondec.push(
                                                Number(el.credit)
                                            );
                                        }
                                    }
                                });
                            }
                        }
                    });
                    _this.tempCommonDisease.push(
                        _.uniq(_.concat(_this.CommonDisease))
                    );

                    var now = new Date(year, month, day),
                        DAY_MS = 86400000,
                        dates = [];
                    for (var i = 0; i < 7; i++) {
                        dates.push(new Date(now.getTime() - i * DAY_MS));
                    }
                    dates.forEach(element => {
                        var year = element.getFullYear();
                        var month = element.getMonth();
                        var day = element.getDate();
                        if (month < 10) {
                            month = "0" + month;
                        }
                        if (day < 10) {
                            day = "0" + day;
                        }
                        var lastweekdate = year + "-" + month + "-" + day;
                        _this.medicalData.forEach(lastweek => {
                            if (lastweek.admission_date == lastweekdate) {
                                _this.lastweekDisease.push(
                                    lastweek.final_diagnosis
                                );
                            }
                        });
                    });
                    _this.chartDataCommonDisease = _this.yesterdayDisease;
                    _this.chartCommonDisease();

                    _this.chartDataReceivePF.push(
                        Number(_.sum(_this.monjan).toFixed(4)),
                        Number(_.sum(_this.monfeb).toFixed(4)),
                        Number(_.sum(_this.monmar).toFixed(4)),
                        Number(_.sum(_this.monapr).toFixed(4)),
                        Number(_.sum(_this.monmay).toFixed(4)),
                        Number(_.sum(_this.monjune).toFixed(4)),
                        Number(_.sum(_this.monjuly).toFixed(4)),
                        Number(_.sum(_this.monaug).toFixed(4)),
                        Number(_.sum(_this.monsept).toFixed(4)),
                        Number(_.sum(_this.monoct).toFixed(4)),
                        Number(_.sum(_this.monnov).toFixed(4)),
                        Number(_.sum(_this.mondec).toFixed(4))
                    );
                    _this.chartReceivePF();

                    var totalComputedPF = 0;
                    var totalPooledFee = 0;
                    _this.medicalData.forEach(element => {
                        var admission_month = element.admission_date;
                        var admission_month_split = admission_month.split("-");
                        if (
                            admission_month_split[0] +
                                "-" +
                                admission_month_split[1] ==
                                year + "-" + month &&
                            typeof element.contributions !== "undefined"
                        ) {
                            totalPooledFee += Number(element.pooled_fee);

                            element.contributions.forEach(el => {
                                if (el.status == "paid") {
                                    totalComputedPF += Number(el.credit);
                                }
                            });
                        }
                    });

                    _this.totalPooledFee = "";
                    _this.totalPooledFee =
                        "₱ " + _this.format_number(totalPooledFee.toFixed(4));

                    _this.totalComputedPF = "";
                    _this.totalComputedPF =
                        "₱ " + _this.format_number(totalComputedPF.toFixed(4));
                }
            });
        },
        chartCommonDisease() {
            var ctx = document.getElementById("chartCommonDisease");
            var backgroundColor = [];
            var borderColor = [];
            _.values(_.groupBy(this.chartDataCommonDisease)).map(
                (item, index) => {
                    dynamicColors();
                }
            );

            function dynamicColors() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                var bac_color = "rgb(" + r + "," + g + "," + b + ", 0.5)";
                var bor_color = "rgb(" + r + "," + g + "," + b + ", 0.6)";
                backgroundColor.push(bac_color);
                borderColor.push(bor_color);
            }

            var myChart = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: _.uniq(_.concat(this.chartDataCommonDisease)),
                    datasets: [
                        {
                            label: "# of Votes",
                            data: _.values(
                                _.groupBy(this.chartDataCommonDisease)
                            ).map(d => {
                                return d.length;
                            }),
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {}
                }
            });

            this.chart = myChart;
        },
        chartReceivePF() {
            var backgroundColor = [];
            var borderColor = [];

            for (let index = 1; index < 13; index++) {
                dynamicColors();
            }

            function dynamicColors() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);
                var bac_color = "rgb(" + r + "," + g + "," + b + ", 0.5)";
                var bor_color = "rgb(" + r + "," + g + "," + b + ", 0.8)";
                backgroundColor.push(bac_color);
                borderColor.push(bor_color);
            }

            var ctx = document.getElementById("chartReceivePF");
            var myChart1 = new Chart(ctx, {
                type: "line",
                data: {
                    labels: [
                        "Jan",
                        "Feb",
                        "Mar",
                        "Apr",
                        "May",
                        "Jun",
                        "Jul",
                        "Aug",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Dec"
                    ],
                    datasets: [
                        {
                            label: "Receive PF",
                            data: this.chartDataReceivePF,
                            backgroundColor: backgroundColor,
                            borderColor: borderColor,
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
            this.chartRePF = myChart1;
        },
        getRecentMedicalRecord: function() {
            axios
                .get("user/recentMedicalRecord_get")
                .then(response => {
                    response.data.forEach(element => {
                        if (element.name_suffix == undefined) {
                            element.name_suffix = "";
                        }
                        element.name = this.buildName(
                            element.first_name,
                            element.middle_name,
                            element.last_name,
                            element.name_suffix
                        );
                    });

                    this.data = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
        },
        getRecentContribution: function() {
            axios
                .get("user/recentContribution_get")
                .then(response => {
                    response.data.forEach(element => {
                        if (element.name_suffix == undefined) {
                            element.name_suffix = "";
                        }
                        element.name = this.buildName(
                            element.first_name,
                            element.middle_name,
                            element.last_name,
                            element.name_suffix
                        );
                    });

                    this.data1 = response.data;
                    this.loading = false;
                })
                .catch(function(error) {});
        },
        buildName: function(first_name, middle_name, last_name, suffix) {
            return (
                last_name +
                " " +
                suffix +
                ", " +
                first_name +
                " " +
                middle_name.slice(0, 1) +
                "."
            ).trim();
        }
    }
};
</script>

<style lang="css" scoped>
.text {
    font-size: 14px;
}
.item {
    margin-bottom: 18px;
}
.clearfix:before,
.clearfix:after {
    display: table;
    content: "";
}
.clearfix:after {
    clear: both;
}
.el-card.is-always-shadow,
.el-card.is-hover-shadow:focus,
.el-card.is-hover-shadow:hover {
    box-shadow: 0px 0px 1px 1px rgba(0, 0, 0, 0.1);
}
.el-card {
    border: 1px solid rgba(0, 0, 0, 0);
}

@media only screen and (max-width: 990px) {
    .el-col-12 {
        width: 100% !important;
        margin-bottom: 15px;
    }
}
@media (min-width: 768px) {
    .col-md-6 {
        flex: 0 0 50% !important;
        max-width: 50% !important;
    }
    .con {
        padding-left: 4px !important;
    }
    .med {
        padding-right: 4px !important;
    }
}
@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-bottom: 15px !important;
    }
}
</style>
