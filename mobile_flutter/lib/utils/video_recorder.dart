// Đường dẫn: mobile_flutter/lib/utils/video_recorder.dart

import 'package:camera/camera.dart';

class VideoRecorderHelper {
  static CameraController? _controller;

  static Future<void> initialize(CameraDescription camera) async {
    _controller = CameraController(camera, ResolutionPreset.medium);
    await _controller?.initialize();
  }

  static Future<void> startRecording(String path) async {
    await _controller?.startVideoRecording();
  }

  static Future<XFile?> stopRecording() async {
    return await _controller?.stopVideoRecording();
  }

  static void dispose() {
    _controller?.dispose();
    _controller = null;
  }
}