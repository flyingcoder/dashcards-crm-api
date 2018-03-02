<template>
    <section class="content invoice">
        <div class="content-header">
            <page-header></page-header>
        </div>
        <div class="content-body">
            <section class="buzz-section">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form">
                            <div class="form-group buzz-group">
                                <label for="exampleInputEmail1">
                                    Title
                                </label>
                                <input type="email" class="form-control" id="exampleInputEmail1" />
                            </div>

                            <div class="form-group buzz-group">
                                <label for="exampleInputPassword1">
                                   Description
                                </label>
                                <input type="password" class="form-control" id="exampleInputPassword1" />
                            </div>

                            <div class="form-group buzz-group">
                                <label for="exampleInputEmail1">
                                    Rate
                                </label>
                                <input type="email" class="form-control" id="exampleInputEmail1" />
                            </div>

                            <div class="form-group buzz-group">
                                <label for="exampleInputEmail1">
                                    Quantity
                                </label>
                                <input type="email" class="form-control" id="exampleInputEmail1" />
                            </div>

                            <div class="form-group invoice-buttons">
                                <ul>
                                    <li class="next">  <a href="#"> <span> Previous </span> </a> </li>
                                    <li class="prev">  <a href="#"> <span> Next </span> </a> </li>
                                    <li class="save">  <button type="submit"> <span> Save </span> </button> </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </section>
</template>

<script>
    import PageHeader from '../page-header.vue';

    export default {   

        components: {
          'page-header': PageHeader,
      },

      data () {
        return {


        }
      },

      mounted () {
        

      },

      methods: {

      }

    }
</script>