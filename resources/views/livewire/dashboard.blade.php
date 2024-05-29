<div>

    <div class="container-fluid">

        <!-- breadcrumbs component -->
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0 p-2">
                <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i>
                        Main</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl-4">
                <div class="card overflow-hidden border-top-0">
                    <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-primary" style="width: 90%"></div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <p class="text-muted fw-semibold fs-16 mb-1">Daily average orders</p>
                                <p class="text-muted mb-4">
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-graph-up-arrow me-1"></i> 1.33%
                                    </span>
                                    vs last month
                                </p>
                            </div>
                            <div class="avatar-sm mb-4">
                                <div class="avatar-title bg-primary-subtle text-primary fs-24 rounded">
                                    <i class="bi bi-receipt"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                            <h3 class="mb-0 d-flex"><i class="bi bi-currency-dollar"></i>1,226.71k </h3>
                            <div class="d-flex align-items-end h-100">
                                <div id="daily-orders" data-colors="#007aff"></div>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-4">
                <div class="card overflow-hidden border-top-0">
                    <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-dark" style="width: 40%"></div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <p class="text-muted fw-semibold fs-16 mb-1">Active users</p>
                                <p class="text-muted mb-4"><span class="badge bg-danger-subtle text-danger"><i
                                            class="bi bi-graph-down-arrow me-1"></i> 5.27%</span> vs last
                                    month
                                </p>
                            </div>
                            <div class="avatar-sm mb-4">
                                <div class="avatar-title bg-dark-subtle text-dark fs-24 rounded">
                                    <i class="bi bi-people"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                            <h3 class="mb-0 d-flex"><i class="bi bi-person"></i> 1,226.71k </h3>
                            <div class="d-flex align-items-end h-100">
                                <div id="new-leads-chart" data-colors="#404040"></div>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->

            <div class="col-xl-4">
                <div class="card overflow-hidden border-top-0">
                    <div class="progress progress-sm rounded-0 bg-light" role="progressbar" aria-valuenow="88"
                        aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar bg-danger" style="width: 60%"></div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="">
                                <p class="text-muted fw-semibold fs-16 mb-1">Booked Revenue</p>
                                <p class="text-muted mb-4">
                                    <span class="badge bg-success-subtle text-success"><i
                                            class="bi bi-graph-up-arrow me-1"></i> 11.8%</span>
                                    vs last month
                                </p>
                            </div>
                            <div class="avatar-sm mb-4">
                                <div class="avatar-title bg-danger-subtle text-danger fs-24 rounded">
                                    <i class="bi bi-clipboard-data"></i>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap flex-lg-nowrap justify-content-between align-items-end">
                            <h3 class="mb-0 d-flex"><i class="bi bi-currency-dollar"></i>12,029.71k </h3>
                            <div class="d-flex align-items-end h-100">
                                <div id="booked-revenue-chart" data-colors="#bb3939"></div>
                            </div>
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

        <div class="row">
            <div class="col-lg-4 order-2 order-lg-1">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">Merchant list</h4>
                            <p class="text-muted fw-semibold mb-0">Social Merchant list</p>
                        </div>
                        <a href="javascript:void(0);" class="btn btn-sm btn-light">
                            <i class="mdi mdi-plus"></i>
                        </a>
                    </div><!-- end card-header -->

                    <div class="card-body py-0 my-3" data-simplebar style="max-height: 470px;">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-dribbble" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 0C3.584 0 0 3.584 0 8s3.584 8 8 8c4.408 0 8-3.584 8-8s-3.592-8-8-8zm5.284 3.688a6.802 6.802 0 0 1 1.545 4.251c-.226-.043-2.482-.503-4.755-.217-.052-.112-.096-.234-.148-.355-.139-.33-.295-.668-.451-.99 2.516-1.023 3.662-2.498 3.81-2.69zM8 1.18c1.735 0 3.323.65 4.53 1.718-.122.174-1.155 1.553-3.584 2.464-1.12-2.056-2.36-3.74-2.551-4A6.95 6.95 0 0 1 8 1.18zm-2.907.642A43.123 43.123 0 0 1 7.627 5.77c-3.193.85-6.013.833-6.317.833a6.865 6.865 0 0 1 3.783-4.78zM1.163 8.01V7.8c.295.01 3.61.053 7.02-.971.199.381.381.772.555 1.162l-.27.078c-3.522 1.137-5.396 4.243-5.553 4.504a6.817 6.817 0 0 1-1.752-4.564zM8 14.837a6.785 6.785 0 0 1-4.19-1.44c.12-.252 1.509-2.924 5.361-4.269.018-.009.026-.009.044-.017a28.246 28.246 0 0 1 1.457 5.18A6.722 6.722 0 0 1 8 14.837zm3.81-1.171c-.07-.417-.435-2.412-1.328-4.868 2.143-.338 4.017.217 4.251.295a6.774 6.774 0 0 1-2.924 4.573z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Dribbble <i
                                        class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-info">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 16 16" fill="currentColor" id="twitter">
                                            <path
                                                d="M16 3.539a6.839 6.839 0 0 1-1.89.518 3.262 3.262 0 0 0 1.443-1.813 6.555 6.555 0 0 1-2.08.794 3.28 3.28 0 0 0-5.674 2.243c0 .26.022.51.076.748a9.284 9.284 0 0 1-6.761-3.431 3.285 3.285 0 0 0 1.008 4.384A3.24 3.24 0 0 1 .64 6.578v.036a3.295 3.295 0 0 0 2.628 3.223 3.274 3.274 0 0 1-.86.108 2.9 2.9 0 0 1-.621-.056 3.311 3.311 0 0 0 3.065 2.285 6.59 6.59 0 0 1-4.067 1.399c-.269 0-.527-.012-.785-.045A9.234 9.234 0 0 0 5.032 15c6.036 0 9.336-5 9.336-9.334 0-.145-.005-.285-.012-.424A6.544 6.544 0 0 0 16 3.539z">
                                            </path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Dribbble <i
                                        class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-info h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-behance" viewBox="0 0 16 16">
                                            <path
                                                d="M4.654 3c.461 0 .887.035 1.278.14.39.07.711.216.996.391.286.176.497.426.641.747.14.32.216.711.216 1.137 0 .496-.106.922-.356 1.242-.215.32-.566.606-.997.817.606.176 1.067.496 1.348.922.281.426.461.957.461 1.563 0 .496-.105.922-.285 1.278a2.317 2.317 0 0 1-.782.887c-.32.215-.711.39-1.137.496a5.329 5.329 0 0 1-1.278.176L0 12.803V3h4.654zm-.285 3.978c.39 0 .71-.105.957-.285.246-.18.355-.497.355-.887 0-.216-.035-.426-.105-.567a.981.981 0 0 0-.32-.355 1.84 1.84 0 0 0-.461-.176c-.176-.035-.356-.035-.567-.035H2.17v2.31c0-.005 2.2-.005 2.2-.005zm.105 4.193c.215 0 .426-.035.606-.07.176-.035.356-.106.496-.216s.25-.215.356-.39c.07-.176.14-.391.14-.641 0-.496-.14-.852-.426-1.102-.285-.215-.676-.32-1.137-.32H2.17v2.734h2.305v.005zm6.858-.035c.286.285.711.426 1.278.426.39 0 .746-.106 1.032-.286.285-.215.46-.426.53-.64h1.74c-.286.851-.712 1.457-1.278 1.848-.566.355-1.243.566-2.06.566a4.135 4.135 0 0 1-1.527-.285 2.827 2.827 0 0 1-1.137-.782 2.851 2.851 0 0 1-.712-1.172c-.175-.461-.25-.957-.25-1.528 0-.531.07-1.032.25-1.493.18-.46.426-.852.747-1.207.32-.32.711-.606 1.137-.782a4.018 4.018 0 0 1 1.493-.285c.606 0 1.137.105 1.598.355.46.25.817.532 1.102.958.285.39.496.851.641 1.348.07.496.105.996.07 1.563h-5.15c0 .58.21 1.11.496 1.396zm2.24-3.732c-.25-.25-.642-.391-1.103-.391-.32 0-.566.07-.781.176-.215.105-.356.25-.496.39a.957.957 0 0 0-.25.497c-.036.175-.07.32-.07.46h3.196c-.07-.526-.25-.882-.497-1.132zm-3.127-3.728h3.978v.957h-3.978v-.957z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Behance
                                    <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-primary h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Facebook <i
                                        class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-danger h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Instagram <i
                                        class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-dark h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Github
                                    <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div><!-- end flex -->

                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-success h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                            <path
                                                d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Google
                                    <i class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded">
                                    <span class="avatar-title bg-transparent border border-light text-danger h3 my-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-pinterest" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <a href="javascript:void(0);" class="h4 my-0 fw-semibold text-reset">Pinterest <i
                                        class="mdi mdi-check-decagram text-muted ms-1"></i></a>
                            </div>
                            <a href="javascript:void(0);" class="fs-16 text-dark text-end"><i
                                    class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 order-1 order-lg-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="">
                            <h4 class="card-title">Revenue</h4>
                            <p class="text-muted fw-semibold mb-0">Your Revenue This Week</p>
                        </div><!-- end card-header -->
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
                        <!-- end dropdown -->
                    </div>
                    <div class="card-body">
                        <div class="pt-3 show">
                            <div id="revenue-report" data-colors="#007aff, #3f3f46" class="apex-charts"
                                dir="ltr"></div>

                            <div class="row text-center">
                                <div class="col">
                                    <p class="text-muted mt-3">Current Week</p>
                                    <h3 class="mb-0">
                                        <span>$506.54</span>
                                    </h3>
                                </div>
                                <div class="col">
                                    <p class="text-muted mt-3">Previous Week</p>
                                    <h3 class=" mb-0">
                                        <span>$305.25</span>
                                    </h3>
                                </div>
                                <div class="col">
                                    <p class="text-muted mt-3">Conversation</p>
                                    <h3 class="mb-0">
                                        <span>3.27%</span>
                                    </h3>
                                </div>
                                <div class="col">
                                    <p class="text-muted mt-3">Customers</p>
                                    <h3 class=" mb-0">
                                        <span>3k</span>
                                    </h3>
                                </div>
                            </div>
                        </div>

                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div><!-- end row -->


        <div class="row">
            <div class="col-xxl-4 order-1 order-lg-2">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">Orders Status</h4>
                            <p class="text-muted fw-semibold mb-0">Your Orders</p>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Sales Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Export Report</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Profit</a>
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item">Action</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="timeline-alt p-3">
                            <div class="timeline-item">
                                <i class="mdi mdi-upload bg-info-subtle text-info timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">You sold an
                                        item</a>
                                    <small>Paul Burgess just purchased “Hyper - Admin
                                        Dashboard”!</small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">5 minutes ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-airplane bg-primary-subtle text-primary timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-primary fw-bold mb-1 d-block">Product on
                                        the
                                        Bootstrap Market</a>
                                    <small>Dave Gamache added
                                        <span class="fw-bold">Admin Dashboard</span>
                                    </small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">30 minutes ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-microphone bg-info-subtle text-info timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">Robert
                                        Delaney</a>
                                    <small>Send you message
                                        <span class="fw-bold">"Are you there?"</span>
                                    </small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">2 hours ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-upload bg-primary-subtle text-primary timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-primary fw-bold mb-1 d-block">Audrey
                                        Tobey</a>
                                    <small>Uploaded a photo
                                        <span class="fw-bold">"Error.jpg"</span>
                                    </small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">14 hours ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-upload bg-info-subtle text-info timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">You sold an
                                        item</a>
                                    <small>Paul Burgess just purchased “Hyper - Admin
                                        Dashboard”!</small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">16 hours ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-airplane bg-primary-subtle text-primary timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-primary fw-bold mb-1 d-block">Product on
                                        the
                                        Bootstrap Market</a>
                                    <small>Dave Gamache added
                                        <span class="fw-bold">Admin Dashboard</span>
                                    </small>
                                    <p class="mb-0 pb-2">
                                        <small class="text-muted">22 hours ago</small>
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <i class="mdi mdi-microphone bg-info-subtle text-info timeline-icon"></i>
                                <div class="timeline-item-info">
                                    <a href="javascript:void(0);" class="text-info fw-bold mb-1 d-block">Robert
                                        Delaney</a>
                                    <small>Send you message
                                        <span class="fw-bold">"Are you there?"</span>
                                    </small>
                                    <p class="mb-0">
                                        <small class="text-muted">2 days ago</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-8 order-2 order-lg-1">
                <div class="card">
                    <div class="card-header d-flex justify-content-between flex-wrap align-items-center">
                        <div>
                            <h4 class="card-title">Recent Order</h4>
                            <p class="text-muted fw-semibold mb-0">Order Based on Payment</p>
                        </div>

                        <div class="">
                            <a class="btn btn-outline-secondary me-2">
                                <i class="mdi mdi-filter-outline pe-1 lh-1"></i>Filter
                            </a>
                            <a class="btn btn-outline-primary">
                                See All
                            </a>

                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-capitalize">
                                        <th>Customer</th>
                                        <th>Price</th>
                                        <th>Location</th>
                                        <th>Requested by</th>
                                        <th>Order</th>
                                    </tr>
                                </thead>
                                <!-- end table heading -->

                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-9.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Dana Graves</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792771583</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$98.59</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">America</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Wade Warren</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">Pending
                                                Approval</span>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1 text-capitalize">Floyd Smith</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792772493</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$32.59</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Russia</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Esther Howard</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">Cancelled
                                                Requested</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-6.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Fernanda Azevedo</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792771583</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$18.24</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Brazil</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Brooklyn...</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-4.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Martine Tollmache</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792780452</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$42.24</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Los Angeles</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 text-capitalize">Arlene Mccoy</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary">Pending
                                                Approval</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-11.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Freja Sjöberg</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792776427</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$113.39</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Miami</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Jerome Bell</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">Cancalled
                                                Requested</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-7.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Daniel J. Heim</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792781478</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$10.39</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Indianapolis</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Courtney Henry</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-subtle text-success">Approved</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm">
                                                    <img src="assets/images/users/avatar-5.jpg" alt=""
                                                        class="img-fluid rounded-circle">
                                                </div>
                                                <div class="ps-2">
                                                    <h5 class="mb-1">Sandra Fraser</h5>
                                                    <p class="text-muted fs-6 mb-0">ORD-1562792779615</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">$95.24</span>
                                        </td>
                                        <td>
                                            <h5 class="mb-0 ms-1">Stlouis</h5>
                                        </td>
                                        <td>
                                            <h5 class="mb-0">Guy Hawkins</h5>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger-subtle text-danger">Cancelled
                                                requested</span>
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- end table body -->
                            </table>
                            <!-- end table -->
                        </div>
                    </div>
                </div>
            </div><!-- end col-->
        </div>
        <!-- end row -->
    </div>
</div>
