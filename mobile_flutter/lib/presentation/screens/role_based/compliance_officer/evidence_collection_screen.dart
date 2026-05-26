import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';

class EvidenceCollectionScreen extends StatefulWidget {
  const EvidenceCollectionScreen({super.key});

  @override
  State<EvidenceCollectionScreen> createState() => _EvidenceCollectionScreenState();
}

class _EvidenceCollectionScreenState extends State<EvidenceCollectionScreen> {
  final List<Map<String, dynamic>> _evidence = [
    {'control': 'AC-01', 'name': 'Access Control Policy', 'status': 'Uploaded', 'date': '2024-01-10', 'file': 'policy_ac_01.pdf'},
    {'control': 'CR-02', 'name': 'Encryption Certificate', 'status': 'Pending', 'date': '-', 'file': '-'},
    {'control': 'NS-03', 'name': 'Firewall Configuration', 'status': 'Uploaded', 'date': '2024-01-12', 'file': 'firewall_config.pdf'},
    {'control': 'IR-01', 'name': 'Incident Response Plan', 'status': 'Review', 'date': '2024-01-08', 'file': 'ir_plan_v2.pdf'},
  ];

  Future<void> _uploadEvidence() async {
    final result = await FilePicker.platform.pickFiles();
    if (result != null) {
      // Upload logic here
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Evidence uploaded successfully')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Evidence Collection'),
        actions: [
          IconButton(
            icon: const Icon(Icons.upload_file),
            onPressed: _uploadEvidence,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Quick Upload', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    DropdownButtonFormField<String>(
                      items: const [
                        DropdownMenuItem(value: 'AC-01', child: Text('AC-01 - Access Control')),
                        DropdownMenuItem(value: 'CR-02', child: Text('CR-02 - Cryptography')),
                        DropdownMenuItem(value: 'NS-03', child: Text('NS-03 - Network Security')),
                      ],
                      onChanged: (value) {},
                      decoration: const InputDecoration(labelText: 'Select Control'),
                    ),
                    const SizedBox(height: 12),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: _uploadEvidence,
                        icon: const Icon(Icons.cloud_upload),
                        label: const Text('Upload Evidence'),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _evidence.length,
              itemBuilder: (context, index) {
                final item = _evidence[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: CircleAvatar(
                      backgroundColor: _getStatusColor(item['status']),
                      child: Icon(_getStatusIcon(item['status']), color: Colors.white, size: 20),
                    ),
                    title: Text(item['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text('Control: ${item['control']} • Uploaded: ${item['date']}'),
                    trailing: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        if (item['file'] != '-')
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
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Uploaded': return Colors.green;
      case 'Pending': return Colors.orange;
      case 'Review': return Colors.blue;
      default: return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'Uploaded': return Icons.check;
      case 'Pending': return Icons.hourglass_empty;
      case 'Review': return Icons.rate_review;
      default: return Icons.help;
    }
  }
}