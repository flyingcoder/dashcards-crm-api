<template>
    <modal name="upload-client" transition="nice-modal-fade" @before-open="beforeOpen">
        <section class="content upload-client">
            <v-layout row wrap>
                <div class="buzz-modal-header"> Update Picture </div>
                <div class="buzz-scrollbar" id="buzz-scroll">
                    <el-upload
                      action=""
                      class="avatar-uploader"
                      :http-request="submit"
                      :show-file-list="false"
                      :before-upload="beforeAvatarUpload">
                      <img v-if="imageUrl" :src="imageUrl" class="avatar">
                      <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                    </el-upload>
                    <div class="form-buttons">
                        <el-button style="float: right;" @click="$modal.hide('upload-client')">SAVE</el-button>
                    </div>
                </div>
            </v-layout>
        </section>
    </modal>
</template>
<style>
  .avatar-uploader {
    padding: 0 35%;
  }

  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
</style>

<script>
 export default {
    data() {
      return {
        imageUrl: '',
        id: 0,
        form: [],
        file: {},
      };
    },
    mounted() {
        this.form = new FormData()
    },
    methods: {
        handleRemove(file, fileList) {
            //console.log(file, fileList);
        },
        beforeOpen (event) {
            this.id = event.params.data.id;
        },
        submit(){

            axios.post('api/clients/'+this.id+'/image', this.form)
                 .then( (response) => {
                    console.log(response);
                    this.imageUrl = response.data
                    this.$emit('refresh');
                 });
        },
        beforeAvatarUpload(file) {
            const isJPG = file.type === 'image/jpeg';
            const isPNG = file.type === 'image/png';
            const isLt2M = file.size / 1024 / 1024 < 2;

            if (!isJPG && !isPNG) {
              swal('Oops!','Avatar picture must be JPG or PNG format!', 'error');
              return false;
            }
            if (!isLt2M) {
              swal('Oops!','Avatar picture size can not exceed 2MB!', 'error');
              return false;
            }

            this.form.append('avatar', file);

            this.file = file;

            return true;
        }
    }
  }
</script>