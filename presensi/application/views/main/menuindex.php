
                    <?
					$statement = " AND MENU_PARENT_ID = '0' ";
					$statement.= " AND A.MENU_ID IN (SELECT MENU_ID FROM AKSES_APP_ABSENSI_MENU WHERE AKSES_APP_ABSENSI_ID = ".$this->AKSES_APP_ABSENSI_ID.")";
					// $statement .= $statement_global.$statement_privacy;
					$menu->selectByParams(array(), -1, -1, $statement, "ORDER BY URUT ASC");
					// echo $menu->query;exit();
					
					while($menu->nextRow())
					{
						if(stristr($_SERVER['HTTP_USER_AGENT'], "Mobile") && $menu->getField("MENU_ID") == '04')
						{}
						else
						{
							if($menu->getField("CHILD") > 0 )
							{
								?>
								<li><a href="#"><?=$menu->getField("NAMA")?> xxx<span class="caret"></span></a>
									<ul class="dropdown-menu">
									<?
										$menu_parent = new Menu();
										$statement_parent = " AND MENU_PARENT_ID = '".$menu->getField("MENU_ID")."'";
										$statement_parent .= $statement_global;
										$menu_parent->selectByParams(array(), -1, -1, $statement_parent, " ORDER BY URUT ASC");
										// echo $menu_parent->query;exit;
										while($menu_parent->nextRow())
										{
											$menu_parent_id= $menu_parent->getField("MENU_ID");
											if($this->STATUS_ATASAN != 1 && $menu_parent->getField("STATUS_DEFAULT") == '2')
											{}
											else
											{
												if($menu_parent->getField("CHILD") > 0)
												{
												?>
													<li><a href="#"><?=$menu_parent->getField("NAMA")?> <span class="caret"></span></a>
														<ul class="dropdown-menu">
														<?
															$menu_child = new Menu();
															$statement_child = " AND MENU_PARENT_ID = '".$menu_parent->getField("MENU_ID")."' ";
															$statement_child .= $statement_global;
															$menu_child->selectByParams(array(), -1, -1, $statement_child, " ORDER BY URUT ASC");
															
															while($menu_child->nextRow())
															{
																$menu_child_id= $menu_child->getField("MENU_ID");
																if($menu_child->getField("CHILD") > 0)
																{
																	?>
																	<li><a href="#"><?=$menu_child->getField("NAMA")?> <span class="caret"></span></a>
																		<ul class="dropdown-menu">
																	<?
																	$menu_child_sub = new Menu();
																	$statement_child_sub = "AND MENU_PARENT_ID = '".$menu_child->getField("MENU_ID")."' ";
																	$statement_child_sub .= $statement_global;
																	$menu_child_sub->selectByParams(array(), -1, -1, $statement_child_sub, "ORDER BY URUT ASC");
																	while($menu_child_sub->nextRow())
																	{
																	?>
																		<li><a href="<?=$menu_child_sub->getField("LINK_FILE")?>" class="menu-item" target="mainFrame"><?=$menu_child_sub->getField("NAMA")?></a></li>
																	<?
																	}
																	?>
																		</ul>
																	</li>
																<?
																}
																else
																{

																	if($this->STATUS_ATASAN != 1 && $menu_child->getField("STATUS_DEFAULT") == '2')
																	{}
																	else
																	{
																		if($reqUserGroupId == '0' && $menu_child->getField("STATUS_DEFAULT") == '3')
																		{}
																		else
																		{
																			$menu_child_nama= $menu_child->getField("NAMA");
																			$menu_child_link= $menu_child->getField("LINK_FILE");
																			// kalau dalam if maka masuk ke app bio
																			// if($menu_child_id == "040601" || $menu_child_id == "040602" || $menu_child_id == "040603")
																			// {
																			// 	$menu_child_link= $menu_child->getField("LINK_FILE");
																			// }
																	?>
																			<li><a href="<?=$menu_child_link?>" class="menu-item" target="mainFrame"><?=$menu_child_nama?></a></li>
																	<?
																		}
																	}
																}
															}
														?>
														</ul>
													</li>
												<?
												}
												else
												{
													?>
														<li><a href="<?=$menu_parent->getField("LINK_FILE")?>" class="menu-item" target="mainFrame">ASDASD<?=$menu_parent->getField("NAMA")?></a></li>
													<?
												}
											}
										}
									?>
									</ul>
								</li>
								<?
							}
							else
							{
							?>
								<li><a href="<?=$menu->getField("LINK_FILE")?>" class="utama menu-item" target="mainFrame"><?=$menu->getField("NAMA")?></a></li>
							<?
							}
						}
					}
                    ?>