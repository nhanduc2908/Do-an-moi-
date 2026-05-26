import 'dart:io';
import 'package:image_picker/image_picker.dart';

class ImagePickerHelper {
  static final ImagePicker _picker = ImagePicker();
  
  static Future<File?> pickImageFromCamera() async {
    final XFile? image = await _picker.pickImage(source: ImageSource.camera);
    if (image != null) {
      return File(image.path);
    }
    return null;
  }
  
  static Future<File?> pickImageFromGallery() async {
    final XFile? image = await _picker.pickImage(source: ImageSource.gallery);
    if (image != null) {
      return File(image.path);
    }
    return null;
  }
  
  static Future<List<File>> pickMultipleImages() async {
    final List<XFile> images = await _picker.pickMultiImage();
    return images.map((image) => File(image.path)).toList();
  }
  
  static Future<File?> pickVideoFromCamera() async {
    final XFile? video = await _picker.pickVideo(source: ImageSource.camera);
    if (video != null) {
      return File(video.path);
    }
    return null;
  }
  
  static Future<File?> pickVideoFromGallery() async {
    final XFile? video = await _picker.pickVideo(source: ImageSource.gallery);
    if (video != null) {
      return File(video.path);
    }
    return null;
  }
  
  static Future<File?> pickFile() async {
    final XFile? file = await _picker.pickMedia();
    if (file != null) {
      return File(file.path);
    }
    return null;
  }
}