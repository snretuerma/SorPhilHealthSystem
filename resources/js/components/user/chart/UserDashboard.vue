<template>
  <div class="small">
    <div>
        <el-row :gutter="12">
            <el-col :span="12">
                <el-card class="box-card" shadow="always" style="background-color:rgba(0,0,0,0);">
                    <div slot="header" class="clearfix">
                        <span style="font-size:1rem;">Total computed PF - </span>
                        <el-date-picker
                            v-model="filterTotalComputedPF"
                            @change="updateDataFunction($event,'Total','computedPF')"
                            :clearable = false
                            type="month"
                            placeholder="Pick a month" 
                            size="mini" 
                            style="float: right; width:110px;">
                        </el-date-picker>
                        <a href="user/record">
                           <el-button style="padding: 6px 5px 6px 5px;" type="text">View more</el-button> 
                        </a>
                    </div>
                    <div class="text item" style="font-size:20px;margin-bottom:0px;color:grey;">
                        <strong>
                          {{ totalComputedPF }}
                        </strong>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="12">
                <el-card class="box-card" shadow="always">
                    <div slot="header" class="clearfix">
                        <span style="font-size:1rem;">Total pooled PF - </span>
                         <el-date-picker
                            v-model="filterTotalPooledFee"
                            @change="updateDataFunction($event,'Total','pooledFee')"
                            :clearable = false
                            type="month"
                            placeholder="Pick a month" 
                            size="mini" 
                            style="float: right; width:110px;">
                        </el-date-picker>
                        <a href="user/record">
                            <el-button style="padding: 6px 5px 6px 5px;" type="text">View more</el-button> 
                        </a>
                    </div>
                    <div class="text item" style="font-size:20px;margin-bottom:0px;color:grey;">
                        <strong>
                          {{ totalPooledFee }}
                        </strong>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div><br>
    <div>
        <el-row :gutter="12">
            <el-col :span="12">
                <el-card class="box-card" shadow="always">
                    <el-button size="mini" @click="updateDataFunction($event,'commonDisease','byyesterday')">Yesterday</el-button>
                    <el-button size="mini" @click="updateDataFunction($event,'commonDisease','bylastweek')">Last week</el-button>
                    <el-date-picker
                        v-model="filterCommonDisease"
                        @change="updateDataFunction($event,'commonDisease','bymonth')"
                        :clearable = false
                        type="month"
                        placeholder="Pick a month" 
                        size="mini" 
                        style="float: right; width:110px;">
                    </el-date-picker>
                    <canvas id="chartCommonDisease" width="400" height="300"></canvas>
                </el-card>
            </el-col>
            <el-col :span="12">
                <el-card class="box-card" shadow="always">
                    <el-date-picker
                        v-model="filterReceivePF"
                        @change="updateDataFunction($event,'receivePF','')"
                        :clearable = false
                        type="year"
                        placeholder="Pick a year" 
                        size="mini" 
                        style="float: right; width:110px">
                    </el-date-picker>
                    <canvas id="chartReceivePF" width="400" height="300"></canvas>
                </el-card>
            </el-col>
        </el-row>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
var array = require('lodash/array');
export default {
  data(){
    return {
        filterCommonDisease: '',
        filterReceivePF: '',
        filterTotalComputedPF: '',
        filterTotalPooledFee: '',
        CommonDisease: [],
        tempCommonDisease: [],
        medicalData: [],
        yesterdayDisease: [],
        lastweekDisease: [],
        selectedByMonthDisease: [],
        chartDataCommonDisease: [],
        chartDataReceivePF: [],
        totalComputedPF: '',
        totalPooledFee: '',
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
    }
  },
  created(){  
  },
  beforeMount(){
  },
  mounted () {
    var today = new Date();
    var month = today.getMonth()+1; 
    var year = today.getFullYear();
    this.filterCommonDisease = new Date(year + "-" + month);
    this.filterReceivePF = new Date(year + "-" + month);
    this.filterTotalComputedPF = new Date(year + "-" + month);
    this.filterTotalPooledFee = new Date(year + "-" + month);
    this.getMedicalData();
  },
  methods: {
    format_number(num)
    {
        var num_parts = num.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    },
    updateDataFunction(selecteddate,updatefor,filterby)
    {
        var date = new Date(selecteddate);
        var month = date.getMonth()+1;
        var year = date.getFullYear();
        var day = date.getDate();
        var _this = this;
            if(month < 10){ month = "0" + month; }
            if(day   < 10){ day   = "0" + day;   }
            switch (updatefor) {
                case 'Total': 
                        var totalComputedPF = 0;
                        var totalPooledFee = 0;
                        if(filterby == 'computedPF' || filterby == 'pooledFee') {
                                _this.medicalData.forEach(element=>{
                                    var admission_month = element.admission_date;
                                    var admission_month_split = admission_month.split('-');
                                    if((admission_month_split[0] + '-' + admission_month_split[1] ) == (year + '-' + month) && 
                                      (typeof element.contributions !== 'undefined')){
                                            if(filterby == 'pooledFee'){
                                                totalPooledFee += Number(element.pooled_fee);
                                            } 
                                            if(filterby == 'computedPF'){
                                                element.contributions.forEach(el=>{
                                                    if(el.status=="paid"){
                                                        totalComputedPF += Number(el.credit); 
                                                    }
                                                });  
                                            }
                                    }
                                });
                                if(filterby == 'computedPF'){
                                    _this.totalComputedPF = "";
                                    _this.totalComputedPF = "₱ " + _this.format_number(totalComputedPF.toFixed(2));
                                }
                                if(filterby == 'pooledFee'){
                                    _this.totalPooledFee = "";
                                    _this.totalPooledFee = "₱ " + _this.format_number(totalPooledFee.toFixed(2));
                                }
                        }
                    break;
                case 'commonDisease':
                    if(filterby == "bymonth"){
                        this.chartDataCommonDisease = [];
                        this.medicalData.forEach(element=>{
                            var admission_month = element.admission_date;
                            var admission_month_split = admission_month.split('-');
                            if((year + '-' +month) == (admission_month_split[0] + '-' + admission_month_split[1])){
                                this.chartDataCommonDisease.push(element.final_diagnosis);
                            }
                        });
                        this.chart.destroy();
                        this.chartCommonDisease();
                    }else if(filterby == "byyesterday"){
                        this.chartDataCommonDisease = [];
                        this.chartDataCommonDisease = this.yesterdayDisease;
                        this.chart.destroy();
                        this.chartCommonDisease();
                    }else if(filterby == "bylastweek"){
                        this.chartDataCommonDisease = [];
                        this.chartDataCommonDisease = this.lastweekDisease;
                        this.chart.destroy();
                        this.chartCommonDisease();
                    }
                    break;
                case 'receivePF':
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
                    _this.medicalData.forEach(element=>{
                        var admission_month = element.admission_date;
                        var admission_month_split = admission_month.split('-');
                        if(admission_month_split[0] == year && (typeof element.contributions !== 'undefined')){
                            for (let index = 1; index < 13; index++) {
                                if(index < 10){ index = "0" + index; }
                                element.contributions.forEach(el=>{
                                    var ad_month = element.admission_date;
                                    var ad_month_split = ad_month.split('-');
                                    if(el.status=="paid" && ad_month_split[1] == index ){
                                        if(index=="01"){
                                            _this.monjan.push(Number(el.credit));
                                        }else if(index=="02"){
                                            _this.monfeb.push(Number(el.credit));
                                        }else if(index=="03"){
                                            _this.monmar.push(Number(el.credit));
                                        }else if(index=="04"){
                                            _this.monapr.push(Number(el.credit));
                                        }else if(index=="05"){
                                            _this.monmay.push(Number(el.credit));
                                        }else if(index=="06"){
                                            _this.monjune.push(Number(el.credit));
                                        }else if(index=="07"){
                                            _this.monjuly.push(Number(el.credit));
                                        }else if(index=="08"){
                                            _this.monaug.push(Number(el.credit));
                                        }else if(index=="09"){
                                            _this.monsept.push(Number(el.credit));
                                        }else if(index=="10"){
                                            _this.monoct.push(Number(el.credit));
                                        }else if(index=="11"){
                                            _this.monnov.push(Number(el.credit));
                                        }else if(index=="12"){
                                            _this.mondec.push(Number(el.credit));
                                        }
                                    }
                                });
                                
                            }
                        }
                    });
                    _this.chartDataReceivePF = [];
                    _this.chartDataReceivePF.push(
                        Number(_.sum(_this.monjan).toFixed(2)),
                        Number(_.sum(_this.monfeb).toFixed(2)),
                        Number(_.sum(_this.monmar).toFixed(2)),
                        Number(_.sum(_this.monapr).toFixed(2)),
                        Number(_.sum(_this.monmay).toFixed(2)),
                        Number(_.sum(_this.monjune).toFixed(2)),
                        Number(_.sum(_this.monjuly).toFixed(2)),
                        Number(_.sum(_this.monaug).toFixed(2)),
                        Number(_.sum(_this.monsept).toFixed(2)),
                        Number(_.sum(_this.monoct).toFixed(2)),
                        Number(_.sum(_this.monnov).toFixed(2)),
                        Number(_.sum(_this.mondec).toFixed(2)),
                    );
                    _this.chartRePF.destroy();
                    _this.chartReceivePF();
            }
    },
    getMedicalData()
    {
        var _this = this;
        axios.get("user/get_common_disease").then(function (response) { 
            var date = new Date();
            var month = date.getMonth()+1;
            var year = date.getFullYear();
            var day = date.getDate();
            var yesterday = new Date(Date.now() - 1 * 86400000 - new Date().getTimezoneOffset() * 60000).toISOString().split('T')[0];
            if(month < 10){ month = "0" + month; }
            if(day < 10)  { day = "0"   + day;   }
            if (response.status > 199 && response.status < 203) {
                _this.medicalData = response.data;
                response.data.forEach(element => {
                    _this.CommonDisease.push(element.final_diagnosis);
                    if(element.admission_date == yesterday){
                        _this.yesterdayDisease.push(element.final_diagnosis);
                    }
                    var admission_month = element.admission_date;
                    var admission_month_split = admission_month.split('-');
                    if((year + '-' +month) == (admission_month_split[0] + '-' + admission_month_split[1])){
                        _this.selectedByMonthDisease.push(element.final_diagnosis);
                    }
                    if(admission_month_split[0] == year && (typeof element.contributions !== 'undefined')){
                        for (let index = 1; index < 13; index++) {
                           if(index < 10){ index = "0" + index; }
                           element.contributions.forEach(el=>{
                               var ad_month = element.admission_date;
                               var ad_month_split = ad_month.split('-');
                                if(el.status=="paid" && ad_month_split[1] == index ){
                                  if(index=="01"){
                                      _this.monjan.push(Number(el.credit));
                                  }else if(index=="02"){
                                      _this.monfeb.push(Number(el.credit));
                                  }else if(index=="03"){
                                      _this.monmar.push(Number(el.credit));
                                  }else if(index=="04"){
                                      _this.monapr.push(Number(el.credit));
                                  }else if(index=="05"){
                                      _this.monmay.push(Number(el.credit));
                                  }else if(index=="06"){
                                      _this.monjune.push(Number(el.credit));
                                  }else if(index=="07"){
                                      _this.monjuly.push(Number(el.credit));
                                  }else if(index=="08"){
                                      _this.monaug.push(Number(el.credit));
                                  }else if(index=="09"){
                                      _this.monsept.push(Number(el.credit));
                                  }else if(index=="10"){
                                      _this.monoct.push(Number(el.credit));
                                  }else if(index=="11"){
                                      _this.monnov.push(Number(el.credit));
                                  }else if(index=="12"){
                                      _this.mondec.push(Number(el.credit));
                                  }
                                }
                            });
                        }
                    } 
                });
               _this.tempCommonDisease.push(_.uniq(_.concat(_this.CommonDisease)));
        
                var now = new Date(year, month, day),
                DAY_MS = 86400000,  
                    dates = [];
                for (var i = 0; i < 7; i++) {
                    dates.push(new Date(now.getTime() - (i * DAY_MS)));
                }
                dates.forEach(element=>{
                    var year = element.getFullYear();
                    var month = element.getMonth();
                    var day = element.getDate();
                    if(month < 10){ month = "0" + month; }
                    if(day < 10){ day = "0" + day; }
                    var lastweekdate = year + '-' + month + '-' + day;
                    _this.medicalData.forEach(lastweek=>{
                        if(lastweek.admission_date == lastweekdate){
                        _this.lastweekDisease.push(lastweek.final_diagnosis);
                        }
                    });
                });
            _this.chartDataCommonDisease = _this.yesterdayDisease;
            _this.chartCommonDisease();
            
            _this.chartDataReceivePF.push(
                Number(_.sum(_this.monjan).toFixed(2)),
                Number(_.sum(_this.monfeb).toFixed(2)),
                Number(_.sum(_this.monmar).toFixed(2)),
                Number(_.sum(_this.monapr).toFixed(2)),
                Number(_.sum(_this.monmay).toFixed(2)),
                Number(_.sum(_this.monjune).toFixed(2)),
                Number(_.sum(_this.monjuly).toFixed(2)),
                Number(_.sum(_this.monaug).toFixed(2)),
                Number(_.sum(_this.monsept).toFixed(2)),
                Number(_.sum(_this.monoct).toFixed(2)),
                Number(_.sum(_this.monnov).toFixed(2)),
                Number(_.sum(_this.mondec).toFixed(2)),
            );
            _this.chartReceivePF();

            var totalComputedPF = 0;
            var totalPooledFee = 0;
            _this.medicalData.forEach(element=>{
                var admission_month = element.admission_date;
                var admission_month_split = admission_month.split('-');
                if((admission_month_split[0] + '-' + admission_month_split[1] ) == (year + '-' + month) && (typeof element.contributions !== 'undefined')){
                        //Total pooled fee
                        totalPooledFee += Number(element.pooled_fee);
                        //computed pf
                        element.contributions.forEach(el=>{
                            if(el.status=="paid"){
                                totalComputedPF += Number(el.credit); 
                            }
                        });  
                }
            });
            //total pooled fee
            _this.totalPooledFee = "";
            _this.totalPooledFee = "₱ " + _this.format_number(totalPooledFee.toFixed(2));

            //total computed pf
            _this.totalComputedPF = "";
            _this.totalComputedPF = "₱ " + _this.format_number(totalComputedPF.toFixed(2));

            }
        });
    },
    chartCommonDisease(){
        var ctx = document.getElementById('chartCommonDisease');
        var backgroundColor = []; 
        var borderColor = []; 
        _.values(_.groupBy(this.chartDataCommonDisease)).map((item,index) => { 
           dynamicColors();
        });
    
        function dynamicColors() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            var bac_color = "rgb(" + r + "," + g + "," + b + ", 0.5)";
            var bor_color = "rgb(" + r + "," + g + "," + b + ", 0.6)";
                backgroundColor.push(bac_color);
                borderColor.push(bor_color);
        };

        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: _.uniq(_.concat(this.chartDataCommonDisease)),
                datasets: [{
                    label: '# of Votes',
                    data: _.values(_.groupBy(this.chartDataCommonDisease)).map(d => {return d.length}),
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    /*yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]*/
                }
            }
        });

        this.chart = myChart;
    },
    chartReceivePF(){
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
        };

        var ctx = document.getElementById('chartReceivePF');
        var myChart1 = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Receive PF',
                    data: this.chartDataReceivePF,
                    backgroundColor: backgroundColor,
                    borderColor: borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        this.chartRePF = myChart1;
    }
  },
}
</script>

<style lang="css" scoped>
    .small {
    /*max-width: 800px;
    max-height: 500px; 
    margin:  50px auto;*/
    }
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
        clear: both
    }
    .box-card {
        /* width: 480px;*/
    }
    .el-card.is-always-shadow, .el-card.is-hover-shadow:focus, .el-card.is-hover-shadow:hover{
        box-shadow: 0px 0px 1px 1px rgba(0,0,0,.1);
    }
    .el-card{
        border:1px solid rgba(0,0,0,0);
    }
</style>