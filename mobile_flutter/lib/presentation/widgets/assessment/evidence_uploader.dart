// Đường dẫn: mobile_flutter/lib/presentation/widgets/assessment/evidence_uploader.dart

import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';

class EvidenceUploader extends StatefulWidget {
  final Function(String) onFileUploaded;

  const EvidenceUploader({super.key, required this.onFileUploaded});

  @override
  State<EvidenceUploader> createState() => _EvidenceUploaderState();
}

class _EvidenceUploaderState extends State<EvidenceUploader> {
  String? _fileName;

  Future<void> _uploadFile() async {
    final result = await FilePicker.platform.pickFiles();
    if (result != null) {
      setState(() => _fileName = result.files.single.name);
      widget.onFileUploaded(result.files.single.path!);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        InkWell(
          onTap: _uploadFile,
          child: Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey.shade300, style: BorderStyle.dashed),
              borderRadius: BorderRadius.circular(8),
            ),
            child: Column(
              children: [
                const Icon(Icons.cloud_upload, size: 40, color: Colors.blue),
                const SizedBox(height: 8),
                const Text('Click to upload evidence'),
                if (_fileName != null) ...[
                  const SizedBox(height: 8),
                  Text(_fileName!, style: const TextStyle(fontSize: 12, color: Colors.green)),
                ],
              ],
            ),
          ),
        ),
      ],
    );
  }
}