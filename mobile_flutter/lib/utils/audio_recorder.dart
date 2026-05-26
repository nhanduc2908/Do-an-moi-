// Đường dẫn: mobile_flutter/lib/utils/audio_recorder.dart

import 'package:record/record.dart';

class AudioRecorderHelper {
  static final AudioRecorder _recorder = AudioRecorder();

  static Future<bool> hasPermission() async {
    return await _recorder.hasPermission();
  }

  static Future<void> startRecording(String path) async {
    await _recorder.start(const RecordConfig(), path: path);
  }

  static Future<void> stopRecording() async {
    await _recorder.stop();
  }

  static Future<bool> isRecording() async {
    return await _recorder.isRecording();
  }
}