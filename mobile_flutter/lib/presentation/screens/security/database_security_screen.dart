import 'package:flutter/material.dart';

class DatabaseSecurityScreen extends StatelessWidget {
  const DatabaseSecurityScreen({super.key});

  final List<Map<String, dynamic>> _databases = const [
    {'name': 'Production DB', 'type': 'MySQL', 'encryption': 'Enabled', 'backup': 'Daily', 'status': 'Secure'},
    {'name': 'Analytics DB', 'type': 'PostgreSQL', 'encryption': 'Disabled', 'backup': 'Weekly', 'status': 'Warning'},
    {'name': 'Archive DB', 'type': 'MongoDB', 'encryption': 'Enabled', 'backup': 'Monthly', 'status': 'Secure'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Database Security'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _databases.length,
        itemBuilder: (context, index) {
          final db = _databases[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: db['status'] == 'Secure' ? Colors.green : Colors.orange,
                child: const Icon(Icons.storage, color: Colors.white),
              ),
              title: Text(db['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Type: ${db['type']}'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      _buildDetailRow('Encryption', db['encryption']),
                      _buildDetailRow('Backup Schedule', db['backup']),
                      _buildDetailRow('Status', db['status']),
                      const SizedBox(height: 12),
                      ElevatedButton(
                        onPressed: () {},
                        child: const Text('View Details'),
                      ),
                    ],
                  ),
                ),
              ],
            ),
          );
        },
      ),
    );
  }

  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          SizedBox(width: 100, child: Text(label, style: const TextStyle(fontWeight: FontWeight.bold))),
          Expanded(child: Text(value)),
        ],
      ),
    );
  }
}