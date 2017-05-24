
            var input_vid;
			var canvas;
			var status;
			var framerates;
			var progress;
			var output_video;
			var download
			framerates=8;//for changing video speed
			
	 function RedGenerateVideo(input_v,canvas_id,progressbar,output_v,download_link,status_div){

 			input_vid=document.getElementById(input_v); 
			canvas = document.getElementById(canvas_id);
			progress = document.getElementById(progressbar);
			output_video=document.getElementById(output_v);
			download=document.getElementById(download_link);
			var status=document.getElementById(status_div);
			
			var cw,ch;	

			var back = document.createElement('canvas');

			var backcontext = back.getContext('2d');

			var context = canvas.getContext('2d');


			input_vid.addEventListener('ended',myEndHandler,false);
			input_vid.addEventListener('play',myPlayHandler,false);
			input_vid.addEventListener('pause',myPauseHandler,false);

			// the actual demo code, yaaay
			var last_time = +new Date;
			var video = new BitByte.Video(framerates);

			var flag=0;
			var cnt=1;

	 		

    		function myEndHandler(e) {
        		console.log("Ended");
        		flag=1;
    		}

    		function myPlayHandler(e) {
        		console.log("played");
        		status.innerHTML="Started...";
        		maxcount = (input_vid.duration * 13);//old value was 8
		        console.log(maxcount);
		        progress.setAttribute("max",maxcount);

        		cw = input_vid.clientWidth;
				ch = input_vid.clientHeight;

				canvas.width = cw;
				canvas.height = ch;
				back.width = cw;
				back.height = ch;
				generate();
				// draw(input_vid,context,backcontext,cw,ch);
    		}
    		function myPauseHandler(e) {
        		console.log("paused");
    		}


function draw(v,c,bc,w,h) {	
    // if(v.paused) return false;
    // bc.drawImage(v,0,0,w,h);    
    // var idata = bc.getImageData(0,0,w,h);    
    // c.putImageData(idata,0,0);
    // setTimeout(function(){draw(v,c,bc,w,h);}, 0);

    if(v.paused) 
    return false;
    // First, draw it into the backing canvas
    bc.drawImage(v,0,0,w,h);
    // bc.fillStyle = "#4989c7";
    // Grab the pixel data from the backing canvas
    var idata = bc.getImageData(0,0,w,h);
    // bc.fillStyle = "rgba(0, 0, 200, 0.5)";
    var data = idata.data;
    // Loop through the pixels, turning them grayscale
    for(var i = 0; i < data.length; i+=2) {
        var r = data[i];
        var g = data[i+1];
        var b = data[i+2];
        var brightness = (3*r+4*g+b)>>>3;
        data[i] = brightness;
        data[i+1] = brightness;
        data[i+2] = brightness;
    }
    idata.data = data;
    // Draw the pixels onto the visible canvas
    c.putImageData(idata,0,0);
    // Start over!
    setTimeout(function(){draw(v,c,bc,w,h);}, 0);
}



function generate(){

	video.add(context);
	progress.value++;
	if(flag==0)
	{
		status.innerHTML = "Generating Video"+(cnt++)+" FPS";
		requestAnimationFrame(generate);
	}
	else
	{
		status.innerHTML = "Compiling Video";
		requestAnimationFrame(createdVideo); // well, should probably use settimeout instead	
	}
}
function createdVideo(){
	var start_time = +new Date;
	var output = video.compile();	
	var end_time = +new Date;
	var url = webkitURL.createObjectURL(output);
	// output_video.src = url; //toString converts it to a URL via Object URLs, falling back to DataURL
	download.style.display = '';
	download.href = url;
	status.innerHTML = "Compiled Video in " + (end_time - start_time) + "ms, file size: " + Math.ceil(output.size / 1024) + "KB";
}


};
window.BitByte = (function(){

	function toWebM(frames, outputAsArray){
		var info = checkFrames(frames);

		var CLUSTER_MAX_DURATION = 30000;
		
		var EBML = [
			{
				"id": 0x1a45dfa3, 
				"data": [
					{ 
						"data": 1,
						"id": 0x4286 
					},
					{ 
						"data": 1,
						"id": 0x42f7 
					},
					{ 
						"data": 4,
						"id": 0x42f2 
					},
					{ 
						"data": 8,
						"id": 0x42f3 
					},
					{ 
						"data": "webm",
						"id": 0x4282 
					},
					{ 
						"data": 2,
						"id": 0x4287 
					},
					{ 
						"data": 2,
						"id": 0x4285 
					}
				]
			},
			{
				"id": 0x18538067, 
				"data": [
					{ 
						"id": 0x1549a966, 
						"data": [
							{  
								"data": 1e6, 
								"id": 0x2ad7b1 
							},
							{ 
								"data": "BitByte",
								"id": 0x4d80 
							},
							{ 
								"data": "BitByte",
								"id": 0x5741 
							},
							{ 
								"data": doubleToString(info.duration),
								"id": 0x4489 
							}
						]
					},
					{
						"id": 0x1654ae6b, 
						"data": [
							{
								"id": 0xae, 
								"data": [
									{  
										"data": 1,
										"id": 0xd7 
									},
									{ 
										"data": 1,
										"id": 0x63c5 
									},
									{ 
										"data": 0,
										"id": 0x9c 
									},
									{ 
										"data": "und",
										"id": 0x22b59c 
									},
									{ 
										"data": "V_VP8",
										"id": 0x86 
									},
									{ 
										"data": "VP8",
										"id": 0x258688 
									},
									{ 
										"data": 1,
										"id": 0x83 
									},
									{
										"id": 0xe0,  
										"data": [
											{
												"data": info.width,
												"id": 0xb0 
											},
											{ 
												"data": info.height,
												"id": 0xba 
											}
										]
									}
								]
							}
						]
					},

				]
			}
		 ];

						
		var frameNumber = 0;
		var clusterTimecode = 0;
		while(frameNumber < frames.length){
			
			var clusterFrames = [];
			var clusterDuration = 0;
			do {
				clusterFrames.push(frames[frameNumber]);
				clusterDuration += frames[frameNumber].duration;
				frameNumber++;				
			}while(frameNumber < frames.length && clusterDuration < CLUSTER_MAX_DURATION);
						
			var clusterCounter = 0;			
			var cluster = {
					"id": 0x1f43b675, 
					"data": [
						{  
							"data": clusterTimecode,
							"id": 0xe7 
						}
					].concat(clusterFrames.map(function(webp){
						var block = makeSimpleBlock({
							discardable: 0,
							frame: webp.data.slice(4),
							invisible: 0,
							keyframe: 1,
							lacing: 0,
							trackNum: 1,
							timecode: Math.round(clusterCounter)
						});
						clusterCounter += webp.duration;
						return {
							data: block,
							id: 0xa3
						};
					}))
				}
			
			EBML[1].data.push(cluster);			
			clusterTimecode += clusterDuration;
		}
						
		return generateEBML(EBML, outputAsArray)
	}


	function checkFrames(frames){
		var width = frames[0].width, 
			height = frames[0].height, 
			duration = frames[0].duration;
		for(var i = 1; i < frames.length; i++){
		// 	console.log("Frames : "+frames[i].width+" "+width);
		// 	if(frames[i].width != width) throw "Frame " + (i + 1) + " has a different width";

		// 	if(frames[i].height != height) throw "Frame " + (i + 1) + " has a different height";
		// 	if(frames[i].duration < 0 || frames[i].duration > 0x7fff) throw "Frame " + (i + 1) + " has a weird duration (must be between 0 and 32767)";
			duration += frames[i].duration;
		}
		return {
			duration: duration,
			width: width,
			height: height
		};
	}


	function numToBuffer(num){
		var parts = [];
		while(num > 0){
			parts.push(num & 0xff)
			num = num >> 8
		}
		return new Uint8Array(parts.reverse());
	}

	function strToBuffer(str){

		var arr = new Uint8Array(str.length);
		for(var i = 0; i < str.length; i++){
			arr[i] = str.charCodeAt(i)
		}
		return arr;
	}


	function bitsToBuffer(bits){
		var data = [];
		var pad = (bits.length % 8) ? (new Array(1 + 8 - (bits.length % 8))).join('0') : '';
		bits = pad + bits;
		for(var i = 0; i < bits.length; i+= 8){
			data.push(parseInt(bits.substr(i,8),2))
		}
		return new Uint8Array(data);
	}

	function generateEBML(json, outputAsArray){
		var ebml = [];
		for(var i = 0; i < json.length; i++){
			var data = json[i].data;
			if(typeof data == 'object') data = generateEBML(data, outputAsArray);					
			if(typeof data == 'number') data = bitsToBuffer(data.toString(2));
			if(typeof data == 'string') data = strToBuffer(data);

			if(data.length){
				var z = z;
			}
			
			var len = data.size || data.byteLength || data.length;
			var zeroes = Math.ceil(Math.ceil(Math.log(len)/Math.log(2))/8);
			var size_str = len.toString(2);
			var padded = (new Array((zeroes * 7 + 7 + 1) - size_str.length)).join('0') + size_str;
			var size = (new Array(zeroes)).join('0') + '1' + padded;
			

			ebml.push(numToBuffer(json[i].id));
			ebml.push(bitsToBuffer(size));
			ebml.push(data)
			

		}
		
		if(outputAsArray){
			var buffer = toFlatArray(ebml)
			return new Uint8Array(buffer);
		}else{
			return new Blob(ebml, {type: "video/mp4"});
		}
	}
	
	function toFlatArray(arr, outBuffer){
		if(outBuffer == null){
			outBuffer = [];
		}
		for(var i = 0; i < arr.length; i++){
			if(typeof arr[i] == 'object'){
				toFlatArray(arr[i], outBuffer)
			}else{
				outBuffer.push(arr[i]);
			}
		}
		return outBuffer;
	}
	

	function toBinStr_old(bits){
		var data = '';
		var pad = (bits.length % 8) ? (new Array(1 + 8 - (bits.length % 8))).join('0') : '';
		bits = pad + bits;
		for(var i = 0; i < bits.length; i+= 8){
			data += String.fromCharCode(parseInt(bits.substr(i,8),2))
		}
		return data;
	}

	function generateEBML_old(json){
		var ebml = '';
		for(var i = 0; i < json.length; i++){
			var data = json[i].data;
			if(typeof data == 'object') data = generateEBML_old(data);
			if(typeof data == 'number') data = toBinStr_old(data.toString(2));
			
			var len = data.length;
			var zeroes = Math.ceil(Math.ceil(Math.log(len)/Math.log(2))/8);
			var size_str = len.toString(2);
			var padded = (new Array((zeroes * 7 + 7 + 1) - size_str.length)).join('0') + size_str;
			var size = (new Array(zeroes)).join('0') + '1' + padded;

			ebml += toBinStr_old(json[i].id.toString(2)) + toBinStr_old(size) + data;

		}
		return ebml;
	}

	function makeSimpleBlock(data){
		var flags = 0;
		if (data.keyframe) flags |= 128;
		if (data.invisible) flags |= 8;
		if (data.lacing) flags |= (data.lacing << 1);
		if (data.discardable) flags |= 1;
		if (data.trackNum > 127) {
			throw "TrackNumber > 127 not supported";
		}
		var out = [data.trackNum | 0x80, data.timecode >> 8, data.timecode & 0xff, flags].map(function(e){
			return String.fromCharCode(e)
		}).join('') + data.frame;

		return out;
	}


	function parseWebP(riff){
		var VP8 = riff.RIFF[0].WEBP[0];
		
		var frame_start = VP8.indexOf('\x9d\x01\x2a'); //A VP8 keyframe starts with the 0x9d012a header
		for(var i = 0, c = []; i < 4; i++) c[i] = VP8.charCodeAt(frame_start + 3 + i);
		
		var width, horizontal_scale, height, vertical_scale, tmp;
		
		tmp = (c[1] << 8) | c[0];
		width = tmp & 0x3FFF;
		horizontal_scale = tmp >> 14;
		tmp = (c[3] << 8) | c[2];
		height = tmp & 0x3FFF;
		vertical_scale = tmp >> 14;
		return {
			width: width,
			height: height,
			data: VP8,
			riff: riff
		}
	}


	function parseRIFF(string){
		var offset = 0;
		var chunks = {};
		
		while (offset < string.length) {
			var id = string.substr(offset, 4);
			var len = parseInt(string.substr(offset + 4, 4).split('').map(function(i){
				var unpadded = i.charCodeAt(0).toString(2);
				return (new Array(8 - unpadded.length + 1)).join('0') + unpadded
			}).join(''),2);
			var data = string.substr(offset + 4 + 4, len);
			offset += 4 + 4 + len;
			chunks[id] = chunks[id] || [];
			
			if (id == 'RIFF' || id == 'LIST') {
				chunks[id].push(parseRIFF(data));
			} else {
				chunks[id].push(data);
			}
		}
		return chunks;
	}

	function doubleToString(num){
		return [].slice.call(
			new Uint8Array(
				(
					new Float64Array([num]) 
				).buffer) 
			, 0) 
			.map(function(e){ 
				return String.fromCharCode(e) 
			})
			.reverse() 
			.join('') 
	}

	function BitByteVideo(speed, quality){ 
		this.frames = [];
		this.duration = 1000 / speed;
		this.quality = quality || 0.8;
	}

	BitByteVideo.prototype.add = function(frame, duration){
		if(typeof duration != 'undefined' && this.duration) throw "you can't pass a duration if the fps is set";
		if(typeof duration == 'undefined' && !this.duration) throw "if you don't have the fps set, you ned to have durations here."
		if('canvas' in frame){ 
			frame = frame.canvas;	
		}
		if('toDataURL' in frame){
			frame = frame.toDataURL('image/webp', this.quality)
		}else if(typeof frame != "string"){
			throw "frame must be a a HTMLCanvasElement, a CanvasRenderingContext2D or a DataURI formatted string"
		}
		if (!(/^data:image\/webp;base64,/ig).test(frame)) {
			throw "Input must be formatted properly as a base64 encoded DataURI of type image/webp";
		}
		this.frames.push({
			image: frame,
			duration: duration || this.duration
		})
	}
	
	BitByteVideo.prototype.compile = function(outputAsArray){
		return new toWebM(this.frames.map(function(frame){
			var webp = parseWebP(parseRIFF(atob(frame.image.slice(23))));
			webp.duration = frame.duration;
			return webp;
		}), outputAsArray)
	}

	return {
		Video: BitByteVideo,
		fromImageArray: function(images, fps, outputAsArray){
			return toWebM(images.map(function(image){
				var webp = parseWebP(parseRIFF(atob(image.slice(23))))
				webp.duration = 1000 / fps;
				return webp;
			}), outputAsArray)
		},
		toWebM: toWebM
		
	}
})()
