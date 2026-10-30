<li class="visible-xs visible-sm">
																<a data-toggle="collapse" href="#langBar" class="collapsed">
																	<i class="material-icons">language</i>
																	<p>
																		@lang('lang.lang')
																		<b class="caret"></b>
																	</p>
																</a>
																<div class="collapse" id="langBar">
																		<ul class="nav">
																				<li>
																					<a href="/localization/en" >@lang('lang.en')</a>
																				</li>
																				<li>
																						<a href="/localization/ch">@lang('lang.ch')</a>
																				</li>
																		</ul>
																</div>
															</li>
														
															<li class="hidden-xs hidden-sm">
																<a href="#" class="dropdown-toggle" data-toggle="dropdown">
																			<i class="material-icons">language</i>
																			<p class="hidden-lg hidden-md">
																					@lang('lang.lang')<b class="caret"></b>
																			</p>
																</a>
																<ul class="dropdown-menu">
																			<li>
																					<a href="/localization/en">@lang('lang.en')</a>
																			</li>
																			<li>
																					<a href="/localization/ch">@lang('lang.ch')</a>
																			</li>
																	</ul>
															</li>
