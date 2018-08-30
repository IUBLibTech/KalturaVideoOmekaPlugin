mw.kalturaPluginWrapper(function(){
 	
	mw.PluginManager.add('customKaltura', mw.PluginManager.getClass('durationLabel').extend({

			setup: function () {
				this._super();
				this.addBindings();
			},

			addBindings: function() {
				var _this = this;

				this.bind('customKaltura_newSegment', function(e, segment) {
					_this.segmentDuration = segment.duration;
					_this.updateUI();
				});

				this.bind('customKaltura_cleanup', function(e) {
					_this.segmentDuration = null;
					_this.updateUI();
				});
			},

			updateUI: function(duration) {
				this._super(
					Math.floor(
						this.segmentDuration ||
						duration ||
						this.currentDuration
					)
				);
			}

		} ) );

    mw.PluginManager.add( 'customKaltura', mw.KBaseComponent.extend({

		defaultConfig: {
			parent: "controlsContainer",
		 	order: 31,
		 	displayImportance: 'medium',
			prefix: ' / '
		},
		
		contentDuration: 0,

		isSafeEnviornment: function(){
			return !this.embedPlayer.isMobileSkin();
		},

		setup: function(){
			var _this = this;
			this.bind( 'durationChange', function(event, duration){
				if( !_this.getPlayer().isInSequence() ){
					_this.contentDuration = duration;
					_this.updateUI( Math.floor(duration) );
				}
			});
			// Support duration for Ads
			this.bind( 'AdSupport_AdUpdateDuration', function(e, duration){
				_this.updateUI( duration );
			});
			this.bind( 'AdSupport_EndAdPlayback', function(){
				_this.updateUI( _this.contentDuration );
			});
		},
		updateUI: function( duration ){
			var formatTime = mw.seconds2npt( parseFloat( duration ) )
			var duration = this.getConfig('prefix') !== undefined ? this.getConfig('prefix') + formatTime : formatTime;
			this.getComponent().text( duration );
		},
		getComponent: function() {
			if( !this.$el ) {
				var duration = this.getConfig('prefix') !== undefined ? this.getConfig('prefix') + "0:0:00 : 0:00:00";
				this.$el = $( '<div />' )
							.addClass ( "timers" + this.getCssClass() )
							.text( duration );
			}
			return this.$el;
		},
		show: function() {
			this.getComponent().css('display','inline').removeData( 'forceHide' );
		},
		setup: function(){
		            // initialization code goes here.
		            // call a method for event bindings:
		            this.addBindings();             
		        },
		        addBindings: function() {
		            this.bind('playerReady', function(){
		            });
		        },

    }));
 
});

