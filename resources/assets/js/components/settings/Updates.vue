 <template>
    <section class="content updates">
        <v-layout row wrap>
            <div class="content-header col-md-12">
                <div class="page-title">
                    <h1> Updates </h1>
                </div>
            </div>
            <div class="col-md-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="text-version">
                            <div class="col-md-4">
                                Your Version
                            </div>
                            <div class="col-md-8">
                                Latest Version
                            </div>
                        </div>
                        <div class="number-version">
                            <div class="col-md-4">
                                1.04
                            </div>
                            <div class="col-md-8">
                                1.05
                            </div>
                        </div>
                        <div class="updates-info">
                            <div class="col-md-12">
                                <h2> A new update is available </h2>
                                <ul class="dashed">
                                    <li>Download the file using the link below</li>
                                    <li>Unzip the file</li>
                                    <li>Upload the "<span>contents</span>" of the unzipped folder into your "<span>mainFTP</span>" directory <br>
                                        (i.e.to over-write folders with same name in your FTP)
                                    </li>
                                </ul>
                                <h3> WARNING: Always backup your website and database before doing an update. </h3>
                                <button>
                                    Download Update
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout>
    </section>
</template>