<div id="poy" class="pagemodal-wrapper">
    <div class="container">
        <div class="pagemodal">
            <div class="head">
                <i class="fas fa-list-ol"></i> เลขชุด - ดึงโพย <a class="btn-close triggerPoy" href="javascript:;"></a>
            </div>
            <div class="content">
                <div class="content-scroll">
                    <ul id="poytab" class="nav nav-tabs" role="tablist">
                        <li class="nav-item w-50 text-center">
                            <a class="nav-link mysetnumber active" href="#poy1" role="tab" data-toggle="tab">เลขชุด</a>
                        </li>
                        <li class="nav-item w-50 text-center">
                            <a class="nav-link mypoy" href="#poy2" role="tab" data-toggle="tab">ดึงโพย</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane text-center active" id="poy1">
                            <div class="d-flex justify-content-between mb-2">
                                <a href="#" class="btn btn-primary btn-sm rounded-0"><i class="fas fa-edit"></i>
                                    จัดการเลขชุด</a>
                                <a href="#" class="btn btn-success btn-sm rounded-0"><i class="fas fa-plus-square"></i>
                                    สร้างเลขชุด</a>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-sm rounded-0"
                                           placeholder="ค้นหาจากชื่อ" aria-label="Recipient's username"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-sm rounded-0" type="button" id="button-addon2">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <table class="table table-striped table-sm" id="numfavorite">
                                <tbody>
                                <tr class="bg-info text-light">
                                    <th class="align-middle text-center">ชื่อชุด</th>
                                    <th class="align-middle text-center">เวลาที่สร้าง</th>
                                    <th class="align-middle text-center">เลข</th>
                                    <th class="align-middle text-right"></th>
                                </tr>
                                <tr class="d-none" id="model_set_number">
                                    <td>{set_name}</td>
                                    <td>{dt}</td>
                                    <td>{set_number}</td>
                                    <td class="text-right">
                                        <a href="#" onclick="pull_to_poy('set_number',{id})" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="poy2">
                            <table class="table table-striped table-hover table-sm" id="mynumpoy">
                                <tbody>
                                    <tr class="bg-dark text-light">
                                        <th class="align-middle text-center">โพย</th>
                                        <th class="align-middle text-center">เวลาที่แทง</th>
                                        <th class="align-middle text-center">เลข</th>
                                        <th class="align-middle text-right"></th>
                                    </tr>
                                    <tr class="d-none" id="model_pull_poy">
                                        <td>{poy_name}</td>
                                        <td>{poy_dt}</td>
                                        <td>{poy_detail}</td>
                                        <td class="text-right">
                                            <a href="#" onclick="pull_to_poy('poy',{poy_id})" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>