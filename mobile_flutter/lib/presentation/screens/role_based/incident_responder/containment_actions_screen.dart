import 'package:flutter/material.dart';

class ContainmentActionsScreen extends StatefulWidget {
  const ContainmentActionsScreen({super.key});

  @override
  State<ContainmentActionsScreen> createState() => _ContainmentActionsScreenState();
}

class _ContainmentActionsScreenState extends State<ContainmentActionsScreen> {
  final List<Map<String, dynamic>> _activeIncidents = [
    {'id': 'INC-001', 'title': 'Suspicious Network Activity', 'severity': 'Critical', 'actions': ['Isolate', 'Block IP', 'Capture Traffic']},
    {'id': 'INC-002', 'title': 'Malware Detection', 'severity': 'High', 'actions': ['Quarantine', 'Scan', 'Remove']},
    {'id': 'INC-003', 'title': 'Unauthorized Access', 'severity': 'Medium', 'actions': ['Revoke Access', 'Reset Password', 'Audit Logs']},
  ];

  void _executeAction(String incidentId, String action) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text('Execute $action'),
        content: Text('This will $action for incident $incidentId. Do you want to proceed?'),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                SnackBar(content: Text('$action executed for $incidentId')),
              );
            },
            child: const Text('Execute'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Containment Actions'),
        backgroundColor: Colors.red.shade700,
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _activeIncidents.length,
        itemBuilder: (context, index) {
          final incident = _activeIncidents[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 16),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: incident['severity'] == 'Critical' ? Colors.red : Colors.orange,
                child: Text(incident['id'].substring(4), style: const TextStyle(color: Colors.white)),
              ),
              title: Text(incident['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Severity: ${incident['severity']}'),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    children: [
                      const Text('Available Containment Actions', style: TextStyle(fontWeight: FontWeight.bold)),
                      const SizedBox(height: 12),
                      Wrap(
                        spacing: 12,
                        runSpacing: 12,
                        children: (incident['actions'] as List<String>).map((action) => ElevatedButton(
                          onPressed: () => _executeAction(incident['id'], action),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: action == 'Isolate' || action == 'Block IP' ? Colors.red : Colors.blue,
                          ),
                          child: Text(action),
                        )).toList(),
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
}