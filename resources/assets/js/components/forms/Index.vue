<template>
    <section class="content questionnaire-forms">
        <v-layout row wrap>
            <div class="content-header col-md-6">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Forms </span> </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                       <div class="form-content">
                            <div class="form-group row">
                                <label for="title" class="title col-sm-1 col-form-label">Title :</label>
                                <div class="col-sm-11">
                                    <input type="text" name="title" class="form-control title-input" id="title">
                                </div>
                            </div>
                            <div class="build-wrap"></div>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>

<script>
	$(document).ready(function(){
		//Generate Form builder
		$('.build-wrap').formBuilder({
			disabledActionButtons: ['data'],
			onSave: function(event, data){
				var datus = {
					title: $('#title').val(),
					questions: data
				};

				axios.post('/api/forms/new', datus)
					 .then( 
					 	( response ) => {
					 		swal('Success!', 'Form is saved!', 'success')
						},
						( error ) => {
							swal('Oops!', error.response.data.message, 'error')
						}
					);
			}
		});
	});
</script>