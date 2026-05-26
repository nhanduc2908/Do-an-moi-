// Đường dẫn: mobile_flutter/lib/utils/image_compressor.dart

import 'dart:io';
import 'package:flutter_image_compress/flutter_image_compress.dart';
import 'package:path_provider/path_provider.dart';

class ImageCompressor {
  static Future<File?> compressImage(File file, {int quality = 80}) async {
    try {
      final directory = await getTemporaryDirectory();
      final targetPath = '${directory.path}/compressed_${DateTime.now().millisecondsSinceEpoch}.jpg';
      
      final result = await FlutterImageCompress.compressAndGetFile(
        file.path,
        targetPath,
        quality: quality,
        format: CompressFormat.jpeg,
      );
      
      return result != null ? File(result.path) : null;
    } catch (e) {
      return null;
    }
  }

  static Future<int> getImageSize(File file) async {
    return await file.length();
  }
}