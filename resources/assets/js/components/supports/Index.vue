<template>
    <section class="content support">
        <v-layout row wrap>
            <div class="col-md-6 content-header">
                <div class="page-title">
                    <h1> <span class="prev-path"> Dashboard </span> &nbsp; <img src="img/icons/ArrowRight.svg"> &nbsp; <span class="current"> Support </span> </h1>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="content-body">
                    <section class="buzz-section">
                        <div class="advanced-supp">
                            <h1>Get help with the <span>Advanced Support</span></h1>
                            <el-autocomplete
                                class="inline-input"
                                v-model="state2"
                                :fetch-suggestions="querySearch"
                                placeholder="Search Something"
                                :trigger-on-focus="false"
                                @select="handleSelect"
                                >
                            </el-autocomplete>
                        </div>
                        <div class="helpSupport">
                            <h1> Help & Support </h1>
                            <div class="row support-info">
                                <div class="col-md-4">
                                    <img src="/img/icons/support/knowledge.png" alt="Image">
                                    <h3> Knowledge Base </h3>
                                    <p>Read our getting started guides & learn about more advance features</p>
                                </div>
                                <div class="col-md-4">
                                    <img src="/img/icons/support/community.png" alt="Image">
                                    <h3> Community Forums</h3>
                                    <p> Help us build a community around Buzzooka get help fromt other Buzzooka Users. We actively participate on the forums</p>
                                </div>
                                <div class="col-md-4">
                                    <img src="/img/icons/support/knowledge.png" alt="Image">
                                    <h3> Contact Us </h3>
                                    <p> Contact our support directly via email, phone, voice chat & phone. We are waiting for your feedback</p>
                                </div>
                            </div>
                            <div class="row browse-article">
                                <div class="col-md-4">
                                    <a href=""> Browse Article </a>
                                </div>
                                <div class="col-md-4">
                                    <a href=""> Browse Article </a>
                                </div>
                                <div class="col-md-4">
                                <a href=""> Browse Article </a>
                                </div>
                            </div>
                            <div class="row apidocs">
                                <div class="col-md-10">
                                    <h3> Build with Buzzooka </h3>
                                    <p> Documentation and resources for developers building applications with Buzzooka API</p>
                                    <div class="api-buttons">
                                        <button class="reg-btn"> Register an Application </button>
                                        <button> Read the API docs </button>
                                    </div>
                                </div>
                            <div class="col-md-2">
                                    <img src="/img/icons/support/cog.png" alt="Image">
                            </div>
                            </div>
                            <div class="row support-options">
                                <div class="col-md-12">
                                    <ul>
                                        <li><a href="#">
                                            <div class="icon-container"> 
                                                <svg width="43px" height="33px" style="margin-top: 5px;">
                                                    <path d="M32.375,18.194 C34.606,16.692 37.315,14.902 40.503,12.823 C41.392,12.236 42.172,11.582 42.843,10.860 L42.843,28.285 L32.375,18.194 ZM38.842,10.409 C35.055,12.942 32.198,14.854 30.258,16.154 C29.191,16.867 28.398,17.399 27.889,17.740 C27.814,17.791 27.696,17.872 27.540,17.980 C27.371,18.097 27.157,18.245 26.895,18.428 C26.388,18.781 25.966,19.067 25.630,19.285 C25.295,19.503 24.890,19.748 24.414,20.018 C23.938,20.289 23.489,20.493 23.068,20.628 C22.647,20.763 22.257,20.830 21.898,20.830 L21.874,20.830 L21.851,20.830 C21.492,20.830 21.102,20.763 20.681,20.628 C20.260,20.493 19.811,20.289 19.335,20.018 C18.859,19.748 18.453,19.504 18.118,19.285 C17.783,19.067 17.361,18.781 16.854,18.428 C16.591,18.245 16.378,18.097 16.209,17.980 C16.052,17.872 15.934,17.791 15.859,17.740 C15.206,17.297 14.415,16.763 13.497,16.147 C12.421,15.424 11.168,14.585 9.728,13.623 C7.060,11.841 5.460,10.770 4.930,10.409 C3.963,9.777 3.050,8.909 2.192,7.804 C1.334,6.697 0.905,5.671 0.905,4.724 C0.905,3.551 1.228,2.574 1.876,1.792 C2.523,1.010 3.447,0.619 4.649,0.619 L39.098,0.619 C40.113,0.619 40.990,0.972 41.732,1.679 C42.472,2.386 42.843,3.236 42.843,4.228 C42.843,5.416 42.461,6.552 41.697,7.634 C40.933,8.716 39.981,9.642 38.842,10.409 ZM3.268,12.823 C6.582,14.993 9.282,16.781 11.377,18.190 L0.904,28.285 L0.904,10.860 C1.591,11.597 2.379,12.251 3.268,12.823 ZM14.899,20.605 C15.789,21.236 16.510,21.729 17.064,22.082 C17.618,22.436 18.355,22.796 19.276,23.164 C20.196,23.533 21.054,23.717 21.850,23.717 L21.874,23.717 L21.897,23.717 C22.693,23.717 23.551,23.533 24.471,23.164 C25.392,22.796 26.129,22.436 26.683,22.082 C27.237,21.729 27.959,21.236 28.848,20.605 C29.109,20.422 29.385,20.231 29.671,20.035 L41.553,31.488 C40.858,32.080 40.041,32.380 39.098,32.380 L4.649,32.380 C3.706,32.380 2.889,32.080 2.195,31.488 L14.079,20.032 C14.376,20.237 14.651,20.429 14.899,20.605 Z"/>
                                                </svg>
                                            </div>
                                            <h6> E-mail Support </h6> </a>
                                        </li>
                                        <li><a href="#"> 
                                            <div class="icon-container">
                                                <svg width="38px" height="38px">
                                                    <path d="M18.923,0.617 C8.802,0.617 0.568,8.492 0.568,18.171 C0.568,21.555 1.575,24.832 3.485,27.666 C3.123,31.917 2.154,35.072 0.747,36.567 C0.561,36.764 0.514,37.066 0.632,37.316 C0.736,37.539 0.950,37.676 1.180,37.676 C1.208,37.676 1.236,37.674 1.265,37.670 C1.512,37.633 7.269,36.752 11.432,34.199 C13.796,35.212 16.315,35.726 18.923,35.726 C29.044,35.726 37.278,27.851 37.278,18.171 C37.278,8.492 29.044,0.617 18.923,0.617 ZM10.357,20.772 C9.007,20.772 7.910,19.606 7.910,18.171 C7.910,16.737 9.007,15.571 10.357,15.571 C11.707,15.571 12.805,16.737 12.805,18.171 C12.805,19.606 11.707,20.772 10.357,20.772 ZM18.923,20.772 C17.573,20.772 16.476,19.606 16.476,18.171 C16.476,16.737 17.573,15.571 18.923,15.571 C20.273,15.571 21.371,16.737 21.371,18.171 C21.371,19.606 20.273,20.772 18.923,20.772 ZM27.489,20.772 C26.139,20.772 25.042,19.606 25.042,18.171 C25.042,16.737 26.139,15.571 27.489,15.571 C28.839,15.571 29.936,16.737 29.936,18.171 C29.936,19.606 28.839,20.772 27.489,20.772 Z"/>
                                                </svg>
                                            </div>
                                            <h6> Voice Chat </h6> </a>
                                        </li>
                                        <li><a href="#">
                                            <div class="icon-container">
                                                <svg width="28px" height="28px" style="margin-top:6px;">
                                                    <path d="M27.368,21.364 L23.045,17.183 C22.184,16.354 20.758,16.380 19.867,17.241 L17.689,19.347 C17.552,19.273 17.410,19.197 17.260,19.115 C15.885,18.379 14.002,17.369 12.021,15.452 C10.035,13.531 8.989,11.709 8.225,10.378 C8.144,10.237 8.067,10.101 7.991,9.972 L9.452,8.560 L10.171,7.865 C11.063,7.002 11.088,5.623 10.229,4.792 L5.906,0.611 C5.047,-0.220 3.620,-0.195 2.728,0.668 L1.509,1.854 L1.543,1.886 C1.135,2.390 0.793,2.971 0.539,3.598 C0.305,4.194 0.159,4.764 0.092,5.335 C-0.478,9.911 1.684,14.092 7.552,19.767 C15.665,27.610 22.202,27.018 22.484,26.989 C23.098,26.918 23.687,26.776 24.285,26.551 C24.928,26.308 25.529,25.979 26.050,25.585 L26.076,25.608 L27.310,24.439 C28.201,23.576 28.226,22.197 27.368,21.364 Z"/>
                                                </svg>
                                            </div>
                                            <h6> Phone </h6> </a>
                                        </li>
                                        <li><a href="#"> 
                                            <div class="icon-container">
                                                <svg width="33px" height="28px" style="margin-top: 8px;">
                                                    <path d="M30.000,23.000 L19.000,23.000 L19.000,26.000 L22.000,26.000 L22.000,28.000 L11.000,28.000 L11.000,26.000 L14.000,26.000 L14.000,23.000 L3.000,23.000 C1.343,23.000 -0.000,21.657 -0.000,20.000 L-0.000,3.000 C-0.000,1.343 1.343,-0.000 3.000,-0.000 L30.000,-0.000 C31.657,-0.000 33.000,1.343 33.000,3.000 L33.000,20.000 C33.000,21.657 31.657,23.000 30.000,23.000 ZM30.000,3.000 L3.000,3.000 L3.000,20.000 L30.000,20.000 L30.000,3.000 Z"/>
                                                </svg>
                                            </div>
                                            <h6> Remote Chat </h6> </a>
                                        </li>
                                        <li><a href="#">
                                            <div class="icon-container">
                                                <svg width="31px" height="35px" style="margin-top: 3px;">
                                                    <path d="M26.998,31.598 L10.537,31.598 C8.335,31.598 6.533,29.766 6.533,27.527 L6.533,4.071 C6.533,1.832 8.335,0.001 10.537,0.001 L24.074,0.001 L24.074,6.623 L31.001,6.721 L31.001,27.527 C31.001,29.766 29.200,31.598 26.998,31.598 ZM25.472,0.001 L25.610,0.001 L31.001,4.907 L31.001,5.144 L25.472,5.144 L25.472,0.001 ZM5.502,27.527 C5.502,28.933 6.069,30.212 6.980,31.139 L6.984,31.143 C7.896,32.069 9.154,32.646 10.537,32.646 L21.948,32.646 L8.485,34.945 C6.313,35.315 4.237,33.812 3.873,31.605 L0.051,8.472 C-0.313,6.264 1.166,4.154 3.337,3.783 L5.545,3.406 C5.517,3.624 5.502,3.846 5.502,4.071 L5.502,27.527 Z"/>
                                                </svg>
                                            </div>
                                            <h6> Forum </h6> </a>
                                        </li>
                                        <li><a href="#"> 
                                            <div class="icon-container">
                                                <svg width="26px" height="32px" style="margin-top: 5px;">
                                                    <path d="M21.275,31.598 L4.728,31.598 C2.514,31.598 0.703,29.766 0.703,27.527 L0.703,4.071 C0.703,1.832 2.514,0.001 4.728,0.001 L18.336,0.001 L18.336,6.623 L25.300,6.721 L25.300,27.527 C25.300,29.766 23.489,31.598 21.275,31.598 ZM9.000,3.000 L3.000,3.000 L3.000,14.000 L6.000,11.000 L9.000,14.000 L9.000,3.000 ZM19.742,0.001 L19.881,0.001 L25.300,4.907 L25.300,5.144 L19.742,5.144 L19.742,0.001 Z"/>
                                                </svg>
                                            </div>
                                            <h6> K-Base </h6> </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </v-layout >
    </section>
</template>

<script>
  export default {
    data() {
      return {
        links: [],
        state2: '',
      };
    },
    methods: {
      querySearch(queryString, cb) {
        var links = this.links;
        var results = queryString ? links.filter(this.createFilter(queryString)) : links;
        // call callback function to return suggestions
        cb(results);
      },
      createFilter(queryString) {
        return (link) => {
          return (link.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },
      loadAll() {
        return [
          { "value": "vue", "link": "https://github.com/vuejs/vue" },
          { "value": "element", "link": "https://github.com/ElemeFE/element" },
          { "value": "cooking", "link": "https://github.com/ElemeFE/cooking" },
          { "value": "mint-ui", "link": "https://github.com/ElemeFE/mint-ui" },
          { "value": "vuex", "link": "https://github.com/vuejs/vuex" },
          { "value": "vue-router", "link": "https://github.com/vuejs/vue-router" },
          { "value": "babel", "link": "https://github.com/babel/babel" }
         ];
      },
      handleSelect(item) {
        console.log(item);
      }
    },
    mounted() {
      this.links = this.loadAll();
    }
  }
</script>