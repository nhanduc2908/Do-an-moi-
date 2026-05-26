import 'package:flutter/material.dart';

class AuditScheduleScreen extends StatefulWidget {
  const AuditScheduleScreen({super.key});

  @override
  State<AuditScheduleScreen> createState() => _AuditScheduleScreenState();
}

class _AuditScheduleScreenState extends State<AuditScheduleScreen> {
  final List<Map<String, dynamic>> _audits = [
    {'standard': 'ISO 27001', 'type': 'Internal', 'date': '2024-06-15', 'status': 'Pending', 'auditor': 'Internal Team'},
    {'standard': 'GDPR', 'type': 'External', 'date': '2024-07-01', 'status': 'Scheduled', 'auditor': 'Deloitte'},
    {'standard': 'PCI DSS', 'type': 'Internal', 'date': '2024-08-20', 'status': 'Planning', 'auditor': 'Compliance Team'},
    {'standard': 'SOC 2', 'type': 'External', 'date': '2024-09-10', 'status': 'Not Started', 'auditor': 'EY'},
    {'standard': 'HIPAA', 'type': 'Internal', 'date': '2024-10-05', 'status': 'Planning', 'auditor': 'Internal Team'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Audit Schedule'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _showAddAuditDialog(),
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _audits.length,
        itemBuilder: (context, index) {
          final audit = _audits[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getStatusColor(audit['status']),
                child: Text(audit['standard'].substring(0, 2), style: const TextStyle(fontSize: 12, color: Colors.white)),
              ),
              title: Text(audit['standard'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${audit['type']} Audit • Due: ${audit['date']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(audit['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  audit['status'],
                  style: TextStyle(color: _getStatusColor(audit['status']), fontSize: 12),
                ),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Auditor:', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(audit['auditor']),
                      const SizedBox(height: 8),
                      const Text('Scope:', style: TextStyle(fontWeight: FontWeight.bold)),
                      const Text('Full organization compliance assessment'),
                      const SizedBox(height: 8),
                      const Text('Status:', style: TextStyle(fontWeight: FontWeight.bold)),
                      LinearProgressIndicator(
                        value: _getProgressValue(audit['status']),
                        backgroundColor: Colors.grey.shade200,
                      ),
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
                              child: const Text('Prepare'),
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

  void _showAddAuditDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Schedule Audit'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'ISO 27001', child: Text('ISO 27001')),
                DropdownMenuItem(value: 'GDPR', child: Text('GDPR')),
                DropdownMenuItem(value: 'PCI DSS', child: Text('PCI DSS')),
                DropdownMenuItem(value: 'SOC 2', child: Text('SOC 2')),
                DropdownMenuItem(value: 'HIPAA', child: Text('HIPAA')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Standard'),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'Internal', child: Text('Internal')),
                DropdownMenuItem(value: 'External', child: Text('External')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Audit Type'),
            ),
            const SizedBox(height: 12),
            TextFormField(
              decoration: const InputDecoration(labelText: 'Audit Date'),
              readOnly: true,
              onTap: () async {
                final date = await showDatePicker(
                  context: context,
                  firstDate: DateTime.now(),
                  lastDate: DateTime.now().add(const Duration(days: 365)),
                );
              },
            ),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Schedule')),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Pending': return Colors.orange;
      case 'Scheduled': return Colors.blue;
      case 'Planning': return Colors.purple;
      case 'Not Started': return Colors.grey;
      default: return Colors.green;
    }
  }

  double _getProgressValue(String status) {
    switch (status) {
      case 'Pending': return 0.3;
      case 'Scheduled': return 0.5;
      case 'Planning': return 0.2;
      case 'Not Started': return 0.0;
      default: return 1.0;
    }
  }
}