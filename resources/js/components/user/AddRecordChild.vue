<template>
    <div>
         <!-- Header -->
        <div class="row header-top">
            <div class="header-title-parent">
                <span class="header-title">
                <i class="fa fa-file-medical-alt"></i>&nbsp;&nbsp;Add Record
                </span>
            </div>
        </div>
        <!-- End Header -->
        <!-- Search Box -->
        <div class="row" id="search_box" style="margin-bottom: 10px">
            <div class="col-xl-9 col-lg-8 col-md-6 col-sm-12">
               
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                       <el-button
                            class="btn-action block-button"
                            @click="triggerClose"
                        >
                            Back
                        </el-button>
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
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- end table -->
    </div>
</template>
<script>
export default {
    props:['data'],
    data() {
        return {
           
        };
    },
    methods: {
        //function to hide addrecordchild.vue
        triggerClose() {
            //add-close was invoke in recordparent.vue so the emit will 
            //same just call 'add-close'
            this.$emit("add-close");
        },
    },
    mounted(){
      console.log(this.data);
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
