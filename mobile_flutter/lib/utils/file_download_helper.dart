// Đường dẫn: mobile_flutter/lib/utils/file_download_helper.dart

import 'dart:io';
import 'package:dio/dio.dart';
import 'package:path_provider/path_provider.dart';

class FileDownloadHelper {
  static final Dio _dio = Dio();

  static Future<String?> downloadFile(String url, String fileName) async {
    try {
      final directory = await getApplicationDocumentsDirectory();
      final filePath = '${directory.path}/$fileName';
      
      await _dio.download(url, filePath);
      return filePath;
    } catch (e) {
      return null;
    }
  }

  static Future<bool> deleteFile(String filePath) async {
    try {
      final file = File(filePath);
      if (await file.exists()) {
        await file.delete();
        return true;
      }
      return false;
    } catch (e) {
      return false;
    }
  }

  static Future<bool> fileExists(String filePath) async {
    final file = File(filePath);
    return await file.exists();
  }
}