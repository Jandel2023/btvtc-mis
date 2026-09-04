<div class="fi-field-wrp">
    <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="camera-capture">
        <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">Trainee Picture</span>
    </label>
</div>

<div
    id="camera-capture"
    x-data="{
        stream: null,
        error: null,
        captured: false,
        startCamera() {
            if (!navigator.mediaDevices?.getUserMedia) {
                this.error = 'This browser does not support camera access.';
                return;
            }
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
                .then((stream) => {
                    this.stream = stream;
                    this.$refs.video.srcObject = stream;
                })
                .catch(() => {
                    this.error = 'Camera access was blocked. Allow camera permission and try again.';
                });
        },
        stopCamera() {
            this.stream?.getTracks().forEach(track => track.stop());
            this.stream = null;
        },
        takePicture() {
            if (!this.stream) return;

            const video = this.$refs.video;
            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

            canvas.toBlob((blob) => {
                if (!blob) {
                    this.error = 'The camera image could not be captured.';
                    return;
                }
                const file = new File([blob], 'trainee-picture.jpg', { type: 'image/jpeg' });

                // ✅ Upload to Livewire/Filament field
                this.$wire.upload('{{ $statePath }}', file,
                    () => { this.captured = true; this.stopCamera(); },
                    () => { this.error = 'The photo could not be uploaded. Please try again.'; }
                );
            }, 'image/jpeg', 0.9);
        },
    }"
    x-init="startCamera()"
    x-on:close.window="stopCamera()"
    class="take-picture"
    data-state-path="{{ $statePath }}"
>
    <div class="take-picture-preview">
        <video x-ref="video" autoplay playsinline muted></video>
        <div x-show="!stream && !error" class="take-picture-status">Starting camera...</div>
        <div x-show="captured" class="take-picture-success">Photo captured. Save the screening to keep it.</div>
    </div>

    <canvas x-ref="canvas" class="hidden"></canvas>
    <p x-show="error" x-text="error" class="take-picture-error"></p>

    <div class="take-picture-actions">
        <button type="button" x-on:click="takePicture()" x-bind:disabled="!stream || captured">Capture Photo</button>
        <button type="button" class="take-picture-secondary" x-on:click="stopCamera()">Stop Camera</button>
    </div>

    <p class="take-picture-note">Your browser will ask for permission to use the laptop camera.</p>
</div>

<style>
.take-picture { display: grid; gap: 1rem; }
.take-picture-preview { position: relative; aspect-ratio: 4 / 3; border: 1px solid #cbd8cf; border-radius: 0.75rem; background: #13251b; }
.take-picture-preview video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
.take-picture-status, .take-picture-success { position: absolute; inset: 0; display: grid; place-items: center; padding: 1rem; color: #fff; font-size: 0.875rem; text-align: center; }
.take-picture-success { align-items: end; background: linear-gradient(transparent, rgb(14 61 47 / 0.9)); color: #dff5e5; padding-bottom: 1rem; }
.take-picture-error { margin: 0; border-radius: 0.5rem; background: #fff1f1; color: #b42318; font-size: 0.875rem; padding: 0.75rem; }
.take-picture-actions { display: flex; justify-content: center; gap: 0.6rem; }
.take-picture-actions button { border: 0; border-radius: 0.5rem; background: #146c43; color: #fff; cursor: pointer; font-weight: 700; padding: 0.65rem 1rem; }
.take-picture-actions button:disabled { cursor: not-allowed; opacity: 0.5; }
.take-picture-actions .take-picture-secondary { background: #e8efe9; color: #16372a; }
.take-picture-note { margin: 0; color: #607269; font-size: 0.75rem; text-align: center; }
</style>
