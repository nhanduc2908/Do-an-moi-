import 'dart:io';
import 'package:path_provider/path_provider.dart';
import '../../../core/utils/logger.dart';

class FileStorageHelper {
  static FileStorageHelper? _instance;
  Directory? _appDirectory;

  FileStorageHelper._internal();

  static Future<FileStorageHelper> getInstance() async {
    if (_instance == null) {
      _instance = FileStorageHelper._internal();
      await _instance!._init();
    }
    return _instance!;
  }

  Future<void> _init() async {
    _appDirectory = await getApplicationDocumentsDirectory();
    Logger.info('FileStorageHelper initialized at: ${_appDirectory?.path}');
  }

  Future<String> getAppDirectoryPath() async {
    return _appDirectory?.path ?? '';
  }

  Future<File> createFile(String fileName, String content) async {
    final file = File('${_appDirectory?.path}/$fileName');
    await file.writeAsString(content);
    Logger.debug('Created file: $fileName');
    return file;
  }

  Future<File> createFileFromBytes(String fileName, List<int> bytes) async {
    final file = File('${_appDirectory?.path}/$fileName');
    await file.writeAsBytes(bytes);
    Logger.debug('Created file from bytes: $fileName');
    return file;
  }

  Future<String> readFile(String fileName) async {
    final file = File('${_appDirectory?.path}/$fileName');
    if (await file.exists()) {
      return await file.readAsString();
    }
    throw Exception('File not found: $fileName');
  }

  Future<List<int>> readFileAsBytes(String fileName) async {
    final file = File('${_appDirectory?.path}/$fileName');
    if (await file.exists()) {
      return await file.readAsBytes();
    }
    throw Exception('File not found: $fileName');
  }

  Future<bool> deleteFile(String fileName) async {
    final file = File('${_appDirectory?.path}/$fileName');
    if (await file.exists()) {
      await file.delete();
      Logger.debug('Deleted file: $fileName');
      return true;
    }
    return false;
  }

  Future<bool> fileExists(String fileName) async {
    final file = File('${_appDirectory?.path}/$fileName');
    return await file.exists();
  }

  Future<void> createDirectory(String dirName) async {
    final dir = Directory('${_appDirectory?.path}/$dirName');
    if (!await dir.exists()) {
      await dir.create(recursive: true);
      Logger.debug('Created directory: $dirName');
    }
  }

  Future<void> deleteDirectory(String dirName) async {
    final dir = Directory('${_appDirectory?.path}/$dirName');
    if (await dir.exists()) {
      await dir.delete(recursive: true);
      Logger.debug('Deleted directory: $dirName');
    }
  }

  Future<List<FileSystemEntity>> listFiles(String dirName) async {
    final dir = Directory('${_appDirectory?.path}/$dirName');
    if (await dir.exists()) {
      return await dir.list().toList();
    }
    return [];
  }

  Future<String> saveImage(List<int> bytes, String fileName) async {
    final imagesDir = Directory('${_appDirectory?.path}/images');
    if (!await imagesDir.exists()) {
      await imagesDir.create(recursive: true);
    }
    
    final file = File('${imagesDir.path}/$fileName');
    await file.writeAsBytes(bytes);
    Logger.debug('Saved image: $fileName');
    return file.path;
  }

  Future<String> saveReport(String content, String fileName) async {
    final reportsDir = Directory('${_appDirectory?.path}/reports');
    if (!await reportsDir.exists()) {
      await reportsDir.create(recursive: true);
    }
    
    final file = File('${reportsDir.path}/$fileName');
    await file.writeAsString(content);
    Logger.debug('Saved report: $fileName');
    return file.path;
  }

  Future<void> clearTempFiles() async {
    final tempDir = await getTemporaryDirectory();
    if (await tempDir.exists()) {
      await tempDir.delete(recursive: true);
      Logger.debug('Cleared temp files');
    }
  }

  Future<int> getDirectorySize(String dirName) async {
    final dir = Directory('${_appDirectory?.path}/$dirName');
    if (!await dir.exists()) return 0;
    
    int size = 0;
    await for (final entity in dir.list(recursive: true)) {
      if (entity is File) {
        size += await entity.length();
      }
    }
    return size;
  }

  Future<void> cleanOldFiles(int days) async {
    final cutoff = DateTime.now().subtract(Duration(days: days));
    final dir = Directory(_appDirectory?.path ?? '');
    
    if (await dir.exists()) {
      await for (final entity in dir.list(recursive: true)) {
        if (entity is File) {
          final stat = await entity.stat();
          if (stat.modified.isBefore(cutoff)) {
            await entity.delete();
            Logger.debug('Deleted old file: ${entity.path}');
          }
        }
      }
    }
  }
}