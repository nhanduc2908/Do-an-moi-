import 'dart:io';
import 'package:path_provider/path_provider.dart';

class FileHelper {
  static Future<String> getLocalPath() async {
    final directory = await getApplicationDocumentsDirectory();
    return directory.path;
  }
  
  static Future<String> getCachePath() async {
    final directory = await getTemporaryDirectory();
    return directory.path;
  }
  
  static Future<File> createFile(String fileName, String content) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    return await file.writeAsString(content);
  }
  
  static Future<String> readFile(String fileName) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    return await file.readAsString();
  }
  
  static Future<bool> deleteFile(String fileName) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    if (await file.exists()) {
      await file.delete();
      return true;
    }
    return false;
  }
  
  static Future<bool> fileExists(String fileName) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    return await file.exists();
  }
  
  static Future<void> writeBytes(String fileName, List<int> bytes) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    await file.writeAsBytes(bytes);
  }
  
  static Future<List<int>> readBytes(String fileName) async {
    final path = await getLocalPath();
    final file = File('$path/$fileName');
    return await file.readAsBytes();
  }
  
  static Future<void> createDirectory(String dirName) async {
    final path = await getLocalPath();
    final directory = Directory('$path/$dirName');
    if (!await directory.exists()) {
      await directory.create(recursive: true);
    }
  }
  
  static Future<void> deleteDirectory(String dirName) async {
    final path = await getLocalPath();
    final directory = Directory('$path/$dirName');
    if (await directory.exists()) {
      await directory.delete(recursive: true);
    }
  }
  
  static Future<List<FileSystemEntity>> listFiles(String dirName) async {
    final path = await getLocalPath();
    final directory = Directory('$path/$dirName');
    if (await directory.exists()) {
      return directory.list().toList();
    }
    return [];
  }
  
  static String getFileExtension(String fileName) {
    final parts = fileName.split('.');
    return parts.length > 1 ? parts.last : '';
  }
  
  static String getFileNameWithoutExtension(String fileName) {
    final parts = fileName.split('.');
    return parts.first;
  }
}