<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cinematic.bukza.com
 * @since      1.0.0
 *
 * @package    Cinematic
 * @subpackage Cinematic/admin/partials
 */

?>

<div id="cinematicapp">
	<div class="app-header">
		<div class="app-title-block">
			<div class="app-logo" @click="openSliders">
				<img width="48" height="48" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/icons/favicon-96x96.png' ); ?>" />
			</div>
			<div class="app-name" v-if="mode==='sliders'">
				<?php esc_html_e( 'Cinematic', 'cinematic' ); ?>
			</div>
			<div class="app-title pure-form" v-if="mode==='constructor'">
				<div class="form-group">
					<label for="title"><?php esc_html_e( 'Title', 'cinematic' ); ?></label>
					<input id="title" type="text" v-model="title">
				</div>
			</div>
		</div>
		<template v-if="mode==='constructor'">
			<div class="app-commands-left">
				<div>
					<button class="pure-button pure-button-primary" @click="run" v-bind:disabled="slides.length<2"><i class="fa fa-play-circle"></i> <?php esc_html_e( 'Run', 'cinematic' ); ?></button>
					<button class="pure-button" @click="saveSlider()"><i class="fa fa-save"></i> <?php esc_html_e( 'Save', 'cinematic' ); ?></button>
				</div>
				<div class="app-commands-shortcode">
					<div class="app-commands-shortcode_title">
						<?php esc_html_e( 'Shortcode', 'cinematic' ); ?>:
					</div>
					<div class="app-commands-shortcode_value">
						[<?php esc_html_e( 'cinematic', 'cinematic' ); ?> id='{{id}}']
					</div>
					<button class="pure-button" @click="copyShortcode()">
						<i class="fa fa-clipboard"></i>
						<?php esc_html_e( 'Copy', 'cinematic' ); ?>
					</button>
				</div>
			</div>
			<div class="app-commands-right">
				<button class="pure-button" @click="openLibrary"><i class="fa fa-image"></i> <?php esc_html_e( 'Library', 'cinematic' ); ?></button>
				<label for="file-upload" class="pure-button">
					<i class="fa fa-file-upload"></i> <?php esc_html_e( 'Upload Config', 'cinematic' ); ?>
				</label>
				<input id="file-upload" style="display: none" type='file' @change='uploadConfig' />
				<button class="pure-button" @click="downloadConfig()">
					<i class="fa fa-file-download"></i>
					<?php esc_html_e( 'Download Config', 'cinematic' ); ?>
				</button>
				<button class="pure-button" @click="showDeleteConfirmation()">
					<i class="fa fa-trash"></i>
					<?php esc_html_e( 'Delete Slider', 'cinematic' ); ?>
				</button>
			</div>
		</template>
		<template v-else-if="mode!=='sliders'">
			<div class="app-commands-left">
				<button class="pure-button" @click="back"><i class="fa fa-arrow-left"></i> <?php esc_html_e( 'Back', 'cinematic' ); ?></button>
			</div>
		</template>
		<template v-else>
			<div class="app-commands-left">
			</div>
			<div class="app-commands-right">
				<?php esc_html_e( 'Data will not be deleted during plugin uninstallation. You can do it manually:', 'cinematic' ); ?> &nbsp;
				<button class="pure-button" @click="showDeleteAllConfirmation()"><?php esc_html_e( 'Delete all data', 'cinematic' ); ?></button>
			</div>
		</template>
	</div>
	<template v-if="mode==='sliders'">
		<div class="sliders">
			<div class="sliders__item" v-for="(slider,$index) in sliders">
				<div class="sliders__item-shortcode">[<?php esc_html_e( 'cinematic', 'cinematic' ); ?> id='{{slider.id}}']</div>
				<div class="sliders__item-title" @click="openSlider(slider.id)">
					<i class="fa fa-image"></i>
					<div>{{slider.title}}</div>
				</div>
			</div>
			<div class="sliders__create" @click="createSlider()">
				<i class="fa fa-plus"></i>
				<div><?php esc_html_e( 'Create New Slider', 'cinematic' ); ?></div>
			</div>
		</div>
		<a class="youtube-link" href="https://youtu.be/K1YSf7pn1ME" target="_blank"><i class="fab fa-youtube"></i>&nbsp;
					<?php esc_html_e( 'Learn how to create layers in Photoshop', 'cinematic' ); ?></a>
	</template>
	<template v-if="mode==='constructor'">
		<div class="slides-tabs">
			<div class="pure-menu pure-menu-horizontal pure-menu-scrollable">
				<ul class="pure-menu-list">
					<li class="pure-menu-item" v-for="(slide,$index) in slides" :key="slide.id" v-bind:class="{'pure-menu-selected':slideIndex == $index}">
						<a href="#" class="pure-menu-link" @click="setSlide($index)"><?php esc_html_e( 'Slide', 'cinematic' ); ?> {{$index+1}}</a>
					</li>
					<li class="pure-menu-item"><a href="#" class="pure-menu-link" @click="addSlide()"><i class="fa fa-plus"></i><span v-if="slides.length===0"> <?php esc_html_e( 'Add Slide', 'cinematic' ); ?></span></a></li>
				</ul>
			</div>
			<div class="app-settings pure-form">
				<div class="form-group">
					<label for="windowHeight"><?php esc_html_e( 'Height', 'cinematic' ); ?></label>
					<input id="windowHeight" type="number" class="width-45 text-right" placeholder="50" v-model="height">
					<span class="pure-form-message-inline">%</span>
				</div>
				<div class="space"></div>
				<div class="form-group">
					<label for="speed"><?php esc_html_e( 'Speed', 'cinematic' ); ?></label>
					<input id="speed" type="number" step="0.1" class="width-45 text-right" placeholder="4.0" v-model="speed">
					<span class="pure-form-message-inline"><?php esc_html_e( 's', 'cinematic' ); ?></span>
				</div>
				<div class="space"></div>
				<div class="form-group width-50">
					<label for="dots" class="pure-checkbox">
						<input id="dots" type="checkbox" v-model="dots"><?php esc_html_e( 'Dots', 'cinematic' ); ?>
					</label>
				</div>
				<div class="space"></div>
				<div class="form-group width-80">
					<label for="slideshow" class="pure-checkbox">
						<input id="slideshow" type="checkbox" v-bind:checked="false" @click="showProVersion()"> <?php esc_html_e( 'Slideshow', 'cinematic' ); ?>
					</label>
				</div>
			</div>
		</div>
		<div class="slide" v-if="slide">
			<div class="slide-body">
				<div class="slide-settings pure-form">
					<div class="form-group">
						<label for="zoom"><?php esc_html_e( 'Zoom', 'cinematic' ); ?></label>
						<input id="zoom" type="number" step="0.1" class="width-45" placeholder="2.0" v-model="slide.zoom">
						<span class="pure-form-message-inline"><?php esc_html_e( 'x', 'cinematic' ); ?></span>
					</div>
					<div class="space"></div>
					<div class="form-group">
						<label for="timing"><?php esc_html_e( 'Timing', 'cinematic' ); ?></label>
						<select id="timing" v-model="slide.timing">
							<option><?php esc_html_e( 'linear', 'cinematic' ); ?></option>
							<option><?php esc_html_e( 'ease', 'cinematic' ); ?></option>
							<option><?php esc_html_e( 'ease-in', 'cinematic' ); ?></option>
							<option><?php esc_html_e( 'ease-out', 'cinematic' ); ?></option>
							<option><?php esc_html_e( 'ease-in-out', 'cinematic' ); ?></option>
						</select>
					</div>
					<div class="space"></div>
					<div class="form-group">
						<label for="duration"><?php esc_html_e( 'Duration', 'cinematic' ); ?></label>
						<input id="duration" type="number" class="width-45 text-right" placeholder="5" v-model="slide.duration">
						<span class="pure-form-message-inline"><?php esc_html_e( 's', 'cinematic' ); ?></span>
					</div>
				</div>
				<div class="slide-items">
					<div class="slide-item" v-for="(item,$index) in slide.items" :key="item.id">
						<div class="slide-item__content">
							<div class="slide-item__image" v-bind:style="{'background-image':'url('+(item.url)+')'}" v-if="!item.isText" @click="pickImage(item)">
							</div>
							<div class="slide-item__shortcode" v-else>
								<?php esc_html_e( 'HTML', 'cinematic' ); ?>
							</div>
							<div class="slide-item__settings">
								<div class="slide-item__top pure-form">
									<div class="form-group">
										<label for="url"><?php esc_html_e( 'Url', 'cinematic' ); ?></label>
										<input id="url" type="text" placeholder="<?php esc_html_e( '<<< pick an image on the left', 'cinematic' ); ?>" v-model="item.url">
									</div>
									<div class="space"></div>
									<div class="form-group">
										<label for="distance"><?php esc_html_e( 'Distance', 'cinematic' ); ?></label>
										<input id="distance" type="number" step="0.1" class="width-45" placeholder="0..1" v-model="item.distance">
										<span class="pure-form-message-inline"><?php esc_html_e( 'x', 'cinematic' ); ?></span>
									</div>
								</div>
								<div class="slide-item__bottom pure-form">
									<div class="form-group">
										<label for="left"><?php esc_html_e( 'Left', 'cinematic' ); ?></label>
										<input id="left" type="number" class="width-45" placeholder="0" v-model="item.left">
										<span class="pure-form-message-inline"><?php esc_html_e( '%', 'cinematic' ); ?></span>
									</div>
									<div class="space"></div>
									<div class="form-group">
										<label for="top"><?php esc_html_e( 'Top', 'cinematic' ); ?></label>
										<input id="top" type="number" class="width-45" placeholder="0" v-model="item.top">
										<span class="pure-form-message-inline"><?php esc_html_e( '%', 'cinematic' ); ?></span>
									</div>
									<div class="space"></div>
									<div class="form-group">
										<label for="width"><?php esc_html_e( 'Width', 'cinematic' ); ?></label>
										<input id="width" type="number" class="width-45" placeholder="100" v-model="item.width">
										<span class="pure-form-message-inline"><?php esc_html_e( '%', 'cinematic' ); ?></span>
									</div>
									<div class="space"></div>
									<div class="form-group">
										<label for="height"><?php esc_html_e( 'Height', 'cinematic' ); ?></label>
										<input id="height" type="number" class="width-45" placeholder="100" v-model="item.height">
										<span class="pure-form-message-inline"><?php esc_html_e( '%', 'cinematic' ); ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="slide-item__commands">
							<button class="pure-button" @click="deleteItem($index)"><?php esc_html_e( 'Delete Item', 'cinematic' ); ?></button>
							<div class="pull-right">
								<button class="pure-button" v-if="$index!=slide.items.length-1" @click="moveDown($index)"><?php esc_html_e( 'Move down', 'cinematic' ); ?></button>
								<button class="pure-button" v-if="$index!=0" @click="moveUp($index)"><?php esc_html_e( 'Move up', 'cinematic' ); ?></button>
							</div>
						</div>
					</div>
				</div>
				<div class="slide__commands">
					<div>
						<button class="pure-button" @click="addImage()"><?php esc_html_e( 'Add Image', 'cinematic' ); ?></button>
						<button class="pure-button" @click="showProVersion()"><?php esc_html_e( 'Add HTML', 'cinematic' ); ?></button>
					</div>
					<div>
						<button class="pure-button" v-if="slideIndex!=0" @click="moveLeft()"><?php esc_html_e( 'Move left', 'cinematic' ); ?></button>
						<button class="pure-button" v-if="slideIndex!=slides.length-1" @click="moveRight()"><?php esc_html_e( 'Move right', 'cinematic' ); ?></button>
						<button class="pure-button" @click="deleteSlide()"><?php esc_html_e( 'Delete Slide', 'cinematic' ); ?></button>
					</div>
				</div>
			</div>
			<div class="slide-preview">
				<div class="slide-preview__root">
					<div v-bind:style="{ 'padding-top': (height?height:50) + '%' }">
					</div>
					<div class="slide-preview__frame">
						<template v-for="(item,$index) in slide.items">
							<img if="item.url" v-bind:src="item.url" class="slide-preview__item" v-bind:style="{ top: (item.top?item.top:0) + '%', left: (item.left?item.left:0) + '%',  width: (item.width?item.width:100) + '%', height: (item.height?item.height:100) + '%' }" />
						</template>
					</div>
				</div>
			</div>
		</div>
	</template>
	<template v-if="mode==='slideshow'">
		<div id="slider" class="cinematic cinematic-inactive">
			<figure v-for="(slide,$index) in slides">
				<div :key="slide.id" v-bind:data-height="(height?height:50) + '%'" v-bind:data-zoom="(slide.zoom?slide.zoom:2)" v-bind:data-timing="(slide.timing?slide.timing:'ease-out')" v-bind:data-duration="(slide.duration?slide.duration:5)">
					<template v-for="(item,$index) in slide.items">
						<div v-bind:data-distance="item.distance" v-bind:data-left="(item.left?item.left:0)" v-bind:data-top="(item.top?item.top:0)" v-bind:data-width="(item.width?item.width:100)" v-bind:data-height="(item.height?item.height:100)"><img style="width:100%;height:100%" v-bind:src="item.url"/></div>
					</template>
				</div>
			</figure>
		</div>
	</template>
	<template v-if="mode==='library'">
		<div class="library">
			<div v-if="isLoading" class="library__loading">
				<div class="library__loading-text">
					<?php esc_html_e( 'Downloading images...', 'cinematic' ); ?>
				</div>
				<div class="library__loader-wrapper">
					<div class="library__loader-border">
						<div class="library__loader-whitespace">
							<div class="library__loader-line">
							</div>
						</div>
					</div>
				</div>
			</div>
			<template v-else>
					<div class="library__title">
						<?php esc_html_e( 'Click on the slide to add it to your slider', 'cinematic' ); ?>
					</div>
					<div class="library__item" v-for="(item,$index) in library">
						<img  v-bind:src="item.thumb"  @click="addSlideFromLibrary($index)" />
						<div class="library__item_pro" v-if="item.items==null">PRO</div>
					</div>
			</template>
		</div>
	</template>
	<template v-if="deleteConfirmationVisible">
		<div class="delete-confirmation__mask" v-on:click.self="hideDeleteConfirmation()">
			<div class="delete-confirmation">
				<div class="delete-confirmation__body">
					<?php esc_html_e( 'Are you sure that you want to delete this slider?', 'cinematic' ); ?>
				</div>
				<div class="delete-confirmation__bottom">
					<div class="pure-button" @click="hideDeleteConfirmation()"><?php esc_html_e( 'Cancel', 'cinematic' ); ?></div>
					<div class="pure-button pure-button-primary" @click="deleteSlider()"><?php esc_html_e( 'Delete', 'cinematic' ); ?></div>
				</div>
			</div>
		</div>
	</template>
	<template v-if="deleteAllConfirmationVisible">
		<div class="delete-confirmation__mask" v-on:click.self="hideDeleteAllConfirmation()">
			<div class="delete-confirmation">
				<div class="delete-confirmation__body">
					<?php esc_html_e( 'Are you sure that you want to delete all data?', 'cinematic' ); ?>
				</div>
				<div class="delete-confirmation__bottom">
					<div class="pure-button" @click="hideDeleteAllConfirmation()"><?php esc_html_e( 'Cancel', 'cinematic' ); ?></div>
					<div class="pure-button pure-button-primary" @click="deleteAllData()"><?php esc_html_e( 'Delete', 'cinematic' ); ?></div>
				</div>
			</div>
		</div>
	</template>
	<template v-if="proVersionVisible">
		<div class="pro-version__mask" v-on:click.self="hideProVersion()">
			<div class="pro-version">
				<div class="pro-version__body">
					<p>
						<?php esc_html_e( 'Please support the project by purchasing a PRO version of the plugin.', 'cinematic' ); ?>
					</p>
					<p>
						<?php esc_html_e( 'Some features are locked in free version. Thank you for understanding.', 'cinematic' ); ?>
					</p>
					<b><?php esc_html_e( 'In PRO version you will get:', 'cinematic' ); ?></b>
					<ul class="pro-version__features">
						<li><?php esc_html_e( 'Automatic slideshow playback.', 'cinematic' ); ?></li>
						<li><?php esc_html_e( 'Ability to Add text/html/short codes to slides.', 'cinematic' ); ?></li>
						<li><?php esc_html_e( 'Hundreds of ready 3D slides in the library.', 'cinematic' ); ?></li>
						<li><?php esc_html_e( 'Support that will help you to configure your slider.', 'cinematic' ); ?></li>
					</ul>
				</div>
				<div class="pro-version__bottom">
					<div class="pure-button" @click="hideProVersion()"><?php esc_html_e( 'No, I don\'t want to help', 'cinematic' ); ?></div>
					<a class="pure-button pure-button-primary" target="_blank" href="https://codecanyon.net/item/cinematic-3d-parallax-touch-slider-for-wordpress/23379722"><?php esc_html_e( 'Install', 'cinematic' ); ?></a>
				</div>
			</div>
		</div>
	</template>
</div>
