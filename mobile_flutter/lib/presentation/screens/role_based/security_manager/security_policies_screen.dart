import 'package:flutter/material.dart';

class SecurityPoliciesScreen extends StatefulWidget {
  const SecurityPoliciesScreen({super.key});

  @override
  State<SecurityPoliciesScreen> createState() => _SecurityPoliciesScreenState();
}

class _SecurityPoliciesScreenState extends State<SecurityPoliciesScreen> {
  final List<Map<String, dynamic>> _policies = [
    {'name': 'Access Control Policy', 'category': 'Access Control', 'status': 'Active', 'version': '2.0', 'updated': '2024-01-10'},
    {'name': 'Data Protection Policy', 'category': 'Data Security', 'status': 'Active', 'version': '1.5', 'updated': '2024-01-05'},
    {'name': 'Incident Response Policy', 'category': 'Incident', 'status': 'Draft', 'version': '3.0', 'updated': '2024-01-15'},
    {'name': 'Password Policy', 'category': 'Security', 'status': 'Active', 'version': '1.2', 'updated': '2023-12-20'},
    {'name': 'Remote Access Policy', 'category': 'Access Control', 'status': 'Under Review', 'version': '1.0', 'updated': '2024-01-08'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Security Policies'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _policies.length,
        itemBuilder: (context, index) {
          final policy = _policies[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              title: Text(policy['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Version ${policy['version']} • Updated ${policy['updated']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(policy['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  policy['status'],
                  style: TextStyle(color: _getStatusColor(policy['status']), fontSize: 12),
                ),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Category:', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(policy['category']),
                      const SizedBox(height: 8),
                      const Text('Description:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const Text('This policy outlines the security requirements and procedures...'),
                      const SizedBox(height: 16),
                      Row(
                        children: [
                          Expanded(
                            child: OutlinedButton(
                              onPressed: () {},
                              child: const Text('View Details'),
                            ),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: ElevatedButton(
                              onPressed: () {},
                              child: const Text('Approve'),
                            ),
                          ),
                        ],
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

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Active': return Colors.green;
      case 'Draft': return Colors.orange;
      case 'Under Review': return Colors.blue;
      default: return Colors.grey;
    }
  }
}