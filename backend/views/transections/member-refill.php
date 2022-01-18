<div class="widget-box">
	<div class="widget-title bg_lg">
		<span class="icon"><i class="icon-user-md"></i></span>
		<h5>ฝาก-ถอน สมาชิก</h5>
	</div>
	<div class="widget-content ">
		<div class="span8">
			<div class="control-group">
				<form action="#" method="get" class="form-horizontal">
					<div class="control-group">
						<label class="control-label">ค้นหาข้อมูล :</label>
						<div class="controls">
							<input type="text" class="span4" placeholder="ค้นหาข้อมูล" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">เลือกวันที่ :</label>
						<div class="controls">
							<input type="text" data-date="09-06-2018"
								data-date-format="dd-mm-yyyy" value="09-06-2018"
								class="datepicker span4">
						</div>
						<div class="control-group">
							<label class="control-label">ตัวกรอง :</label>
							<div class="controls">
								<select>
									<option>ทั้งหมด</option>
									<option>วันที่ เวลา</option>
									<option>ผู้แจ้ง</option>
									<option>เครดิต</option>
									<option>เบอร์โทร</option>
									<option>ชื่อบัญชี</option>
									<option>เลขที่บัญชี</option>
									<option>เวลาฝาก</option>
									<option>จำนวนเงิน</option>
									<option>หมายเหตุ</option>
									<option>สถานะ</option>
									<option>การกระทำ</option>
								</select>
								<button type="submit" class="btn btn-success">ค้นหา</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="span4">

			<div class="control-group">
				<div class="controls">
					<div class="controls">
						<select>
							<option>ธนาคาร</option>
							<option>ธนาคารกรุงศรีอยุธยา</option>
							<option>ธนาคารทหารไทย</option>
							<option>ธนาคารกสิกรไทย</option>
							<option>ธนาคารกรุงไทย</option>
							<option>ธนาคารกรุงเทพ</option>
							<option>ธนาคารไทยพาณิชย์</option>
						</select>
						<button type="submit" class="btn btn-primary" data-toggle="modal"
							data-target="#account">เพิ่มข้อมูล</button>
					</div>
					<!-- Modal -->
					<div class="modal fade" id="account" role="dialog">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title">เพิ่มรายชื่อธนาคาร</h4>
								</div>
								<div class="modal-body">
									<form action="#" method="get" class="form-horizontal">
										<div class="control-group">
											<label class="control-label">วันที่ทำรายการ :</label>
											<div class="controls">
												<input type="text" data-date="09-06-2018"
													data-date-format="dd-mm-yyyy" value="09-06-2018"
													class="datepicker span8">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">รายชื่อธนาคาร :</label>
											<div class="controls">
												<input type="text" class="span11"
													placeholder="รายชื่อธนาคาร" />
											</div>
										</div>
										<div class="control-group">
											<label class="control-label">เลือกรูปภาพโลโก้ :</label>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="file"
												class="form-control-file" id="exampleFormControlFile1">
										</div>
									</form>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary"
										data-toggle="modal" data-target="#account">บักทึกข้อมูล</button>
									<button type="button" class="btn btn-default"
										data-dismiss="modal">ยกเลิก</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="control-group">
			<div class="controls">
				<div class="controls">
					<select>
						<option>ประเภทรายการ</option>
						<option>ฝาก</option>
						<option>ถอน</option>
					</select>
					<button type="submit" class="btn btn-info" data-toggle="modal"
						data-target="#type">เพิ่มข้อมูล</button>
				</div>
				<!-- Modal -->
				<div class="modal fade" id="type" role="dialog">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">เพิ่มประเภทรายการ</h4>
							</div>
							<div class="modal-body">
								<form action="#" method="get" class="form-horizontal">
									<div class="control-group">
										<label class="control-label">วันที่ทำรายการ :</label>
										<div class="controls">
											<input type="text" data-date="09-06-2018"
												data-date-format="dd-mm-yyyy" value="09-06-2018"
												class="datepicker span8">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">ประเภทรายการ :</label>
										<div class="controls">
											<input type="text" class="span11" placeholder="ประเภทรายการ" />
										</div>
									</div>

								</form>
							</div>
							<div class="modal-footer">
								<button type="submit" class="btn btn-info" data-toggle="modal"
									data-target="#account">บักทึกข้อมูล</button>
								<button type="button" class="btn btn-default"
									data-dismiss="modal">ยกเลิก</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="control-group">
			<div class="controls">
				<div class="controls">
					<select>
						<option>ธนาคารแจ้งฝาก</option>
						<option>ข้อความแจ้งฝาก</option>
					</select>
				</div>
			</div>
		</div>

	</div>
	<div class="widget-box">
		<div class="widget-title">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#tab1">รายการแจ้ง
						ฝาก-ถอน</a></li>
				<li><a data-toggle="tab" href="#tab2">รายการแจ้ง ฝาก-ถอน ย้อนหลัง</a></li>

			</ul>
		</div>
		<div class="widget-content tab-content">
			<div id="tab1" class="tab-pane active">
				<p>
				
				
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>วันที่ เวลา</th>
							<th>ผู้แจ้ง</th>
							<th>เครดิต</th>
							<th>เบอร์โทร</th>
							<th>ประเภท</th>
							<th>ธนาคาร</th>
							<th>ชื่อบัญชี</th>
							<th>เลขที่บัญชี</th>
							<th>เวลาฝาก</th>
							<th>จำนวนเงิน</th>
							<th>ข้อความหมายเหตุ</th>
							<th>สถานะ</th>
							<th>การกระทำ</th>

						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<button type="button" class="btn-mini btn-danger"
									data-toggle="modal" data-target="#status">ปรับสถานะ</button>
							</td>
							<td>
								<button type="button" class="btn-mini btn-warning"
									data-toggle="modal" data-target="#detail_update">รายงานเครดิต</button>
							</td>
							<!-- Modal -->
							<div class="modal fade" id="status" role="dialog">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">ปรับสถานะงาน</h4>
										</div>
										<div class="modal-body">
											<form action="#" method="get" class="form-horizontal">
												<div class="control-group">
													<label class="control-label">วันที่-เวลา :</label>
													<div class="controls">
														<input type="text" data-date="09-06-2018"
															data-date-format="dd-mm-yyyy" value="09-06-2018"
															class="datepicker span5">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">รายชื่อผู้อัพเดตงาน :</label>
													<div class="controls">
														<input type="text" class="span5"
															placeholder="รายชื่อผู้อัพเดตงาน" />
													</div>
												</div>
												<label class="control-label">สถานะงาน :</label>
												<div class="control-group">
													<div class="controls">
														<div class="controls">
															<select>
																<option>รอดำเนินการ</option>
																<option>ดำเนินการแล้ว</option>
															</select>
														</div>
													</div>
													<div class="control-group">
														<label class="control-label">หมายเหตุ :</label>
														<div class="controls">
															<input type="text" class="span11" placeholder="หมายเหตุ" />
														</div>
													</div>
												</div>
											</form>
										</div>
										<div class="modal-footer">
											<button type="submit" class="btn btn-danger"
												data-toggle="modal" data-target="#account">บักทึกข้อมูล</button>
											<button type="button" class="btn btn-default"
												data-dismiss="modal">ยกเลิก</button>
										</div>
									</div>
								</div>
							</div>
							<!-- Modal -->
							<div class="modal fade" id="detail_update" role="dialog">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">ข้อมูลผู้อัพเดตข้อมูล</h4>
										</div>
										<div class="modal-body">
											<form action="#" method="get" class="form-horizontal">
												<div class="control-group">
													<label class="control-label">วันที่-เวลา :</label>
													<div class="controls">
														<input type="text" data-date="09-06-2018"
															data-date-format="dd-mm-yyyy" value="09-06-2018"
															class="datepicker span8">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">รายชื่อผู้อัพเดตงาน :</label>
													<div class="controls">
														<input type="text" class="span11"
															placeholder="รายชื่อผู้อัพเดตงาน" />
													</div>
												</div>

											</form>
										</div>
										<div class="modal-footer">

											<button type="button" class="btn btn-warning"
												data-dismiss="modal">ยกเลิก</button>
										</div>
									</div>
								</div>
							</div>
						</tr>
					</tbody>
				</table>
				<br>
				<br>
				<br>
				</p>

			</div>
			<div id="tab2" class="tab-pane">
				<p>
				
				
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>วันที่ เวลา</th>
							<th>ผู้แจ้ง</th>
							<th>เครดิต</th>
							<th>เบอร์โทร</th>
							<th>ประเภท</th>
							<th>ธนาคาร</th>
							<th>ชื่อบัญชี</th>
							<th>เลขที่บัญชี</th>
							<th>เวลาฝาก</th>
							<th>จำนวนเงิน</th>
							<th>ข้อความหมายเหตุ</th>
							<th>สถานะ</th>
							<th>การกระทำ</th>

						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<button type="button" class="btn-mini btn-success"
									data-toggle="modal" data-target="#detail_update2">รายงานเครดิต</button>
							</td>
							<!-- Modal -->
							<div class="modal fade" id="detail_update2" role="dialog">
								<div class="modal-dialog modal-lg">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal">&times;</button>
											<h4 class="modal-title">ข้อมูลผู้อัพเดตข้อมูล</h4>
										</div>
										<div class="modal-body">
											<form action="#" method="get" class="form-horizontal">
												<div class="control-group">
													<label class="control-label">วันที่-เวลา :</label>
													<div class="controls">
														<input type="text" data-date="09-06-2018"
															data-date-format="dd-mm-yyyy" value="09-06-2018"
															class="datepicker span8">
													</div>
												</div>
												<div class="control-group">
													<label class="control-label">รายชื่อผู้อัพเดตงาน :</label>
													<div class="controls">
														<input type="text" class="span11"
															placeholder="รายชื่อผู้อัพเดตงาน" />
													</div>
												</div>

											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-success"
												data-dismiss="modal">ยกเลิก</button>

										</div>
									</div>
								</div>
							</div>
						</tr>
					</tbody>
				</table>
				</p>
				<br>
				<br>
				<br>
			</div>

		</div>
	</div>
</div>
</div>