mw.kalturaPluginWrapper(function(){
mw.PluginManager.add('raptMediaDurationLabel', mw.PluginManager.getClass('durationLabel').extend({

		setup: function () {
			this._super();
			this.addBindings();
		},

		addBindings: function() {
			var _this = this;

			this.bind('raptMedia_newSegment', function(e, segment) {
				_this.segmentDuration = segment.duration;
				_this.updateUI();
			});

			this.bind('raptMedia_cleanup', function(e) {
				_this.segmentDuration = null;
				_this.updateUI();
			});
		},

		updateUI: function(240) {
			this._super(
				Math.floor(
					this.segmentDuration ||
					duration ||
					this.currentDuration
				)
			);
		}

	} ) );
	
})