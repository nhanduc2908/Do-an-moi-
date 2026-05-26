import 'package:flutter/material.dart';

class IocManagementScreen extends StatefulWidget {
  const IocManagementScreen({super.key});

  @override
  State<IocManagementScreen> createState() => _IocManagementScreenState();
}

class _IocManagementScreenState extends State<IocManagementScreen> {
  final List<Map<String, dynamic>> _iocs = [
    {'type': 'IP', 'value': '185.130.5.253', 'threat': 'C2 Server', 'confidence': 'High', 'date': '2024-01-15', 'status': 'Active'},
    {'type': 'Domain', 'value': 'malware-domain.com', 'threat': 'Malware Distribution', 'confidence': 'High', 'date': '2024-01-14', 'status': 'Active'},
    {'type': 'Hash', 'value': 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'threat': 'Ransomware', 'confidence': 'Critical', 'date': '2024-01-13', 'status': 'Active'},
    {'type': 'URL', 'value': 'http://evil.com/payload', 'threat': 'Phishing', 'confidence': 'Medium', 'date': '2024-01-12', 'status': 'Expired'},
  ];

  void _showAddIocDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Add IOC'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'IP', child: Text('IP Address')),
                DropdownMenuItem(value: 'Domain', child: Text('Domain')),
                DropdownMenuItem(value: 'Hash', child: Text('File Hash')),
                DropdownMenuItem(value: 'URL', child: Text('URL')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'IOC Type'),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'IOC Value'),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Threat Description'),
              maxLines: 2,
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'High', child: Text('High Confidence')),
                DropdownMenuItem(value: 'Medium', child: Text('Medium Confidence')),
                DropdownMenuItem(value: 'Low', child: Text('Low Confidence')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Confidence Level'),
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Add')),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('IOC Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: _showAddIocDialog,
          ),
          IconButton(
            icon: const Icon(Icons.upload),
            onPressed: () {},
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: TextField(
              decoration: InputDecoration(
                hintText: 'Search IOCs...',
                prefixIcon: const Icon(Icons.search),
                border: OutlineInputBorder(
                  borderRadius: BorderRadius.circular(8),
                ),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _iocs.length,
              itemBuilder: (context, index) {
                final ioc = _iocs[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: ListTile(
                    leading: Container(
                      width: 50,
                      height: 50,
                      decoration: BoxDecoration(
                        color: _getTypeColor(ioc['type']).withOpacity(0.1),
                        borderRadius: BorderRadius.circular(8),
                      ),
                      child: Center(
                        child: Text(ioc['type'], style: TextStyle(color: _getTypeColor(ioc['type']), fontWeight: FontWeight.bold)),
                      ),
                    ),
                    title: Text(ioc['value'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text('Threat: ${ioc['threat']}'),
                        Text('Added: ${ioc['date']} • Confidence: ${ioc['confidence']}'),
                      ],
                    ),
                    trailing: PopupMenuButton<String>(
                      onSelected: (value) {},
                      itemBuilder: (context) => [
                        const PopupMenuItem(value: 'edit', child: Text('Edit')),
                        const PopupMenuItem(value: 'delete', child: Text('Delete', style: TextStyle(color: Colors.red))),
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

  Color _getTypeColor(String type) {
    switch (type) {
      case 'IP': return Colors.blue;
      case 'Domain': return Colors.green;
      case 'Hash': return Colors.orange;
      default: return Colors.purple;
    }
  }
}