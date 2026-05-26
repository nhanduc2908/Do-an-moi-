import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';

class AuditEvidenceScreen extends StatefulWidget {
  const AuditEvidenceScreen({super.key});

  @override
  State<AuditEvidenceScreen> createState() => _AuditEvidenceScreenState();
}

class _AuditEvidenceScreenState extends State<AuditEvidenceScreen> {
  final List<Map<String, dynamic>> _evidence = [
    {'control': 'AC-01', 'name': 'Access Control Policy', 'file': 'policy_ac_01.pdf', 'date': '2024-01-15'},
    {'control': 'CR-02', 'name': 'Encryption Certificate', 'file': 'certificate.pdf', 'date': '2024-01-10'},
  ];

  Future<void> _uploadEvidence() async {
    final result = await FilePicker.platform.pickFiles();
    if (result != null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Evidence uploaded successfully')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Audit Evidence'),
        actions: [
          IconButton(
            icon: const Icon(Icons.upload),
            onPressed: _uploadEvidence,
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _evidence.length,
        itemBuilder: (context, index) {
          final item = _evidence[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: const Icon(Icons.description, color: Colors.blue),
              title: Text(item['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Control: ${item['control']} • Uploaded: ${item['date']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  IconButton(
                    icon: const Icon(Icons.download, color: Colors.blue),
                    onPressed: () {},
                  ),
                  IconButton(
                    icon: const Icon(Icons.delete, color: Colors.red),
                    onPressed: () {},
                  ),
                ],
              ),
            ),
          );
        },
      ),
    );
  }
}