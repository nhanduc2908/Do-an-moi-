import 'package:flutter/material.dart';

class RiskTreatmentScreen extends StatefulWidget {
  const RiskTreatmentScreen({super.key});

  @override
  State<RiskTreatmentScreen> createState() => _RiskTreatmentScreenState();
}

class _RiskTreatmentScreenState extends State<RiskTreatmentScreen> {
  final List<Map<String, dynamic>> _treatments = [
    {'risk': 'Data Breach', 'treatment': 'Implement DLP Solution', 'status': 'In Progress', 'owner': 'Security Team', 'due': '2024-02-15'},
    {'risk': 'System Outage', 'treatment': 'Deploy Redundancy', 'status': 'Planned', 'owner': 'IT Team', 'due': '2024-03-01'},
    {'risk': 'Compliance Failure', 'treatment': 'Regular Audits', 'status': 'Completed', 'owner': 'Compliance', 'due': '2024-01-20'},
    {'risk': 'Third-party Breach', 'treatment': 'Vendor Assessment', 'status': 'In Progress', 'owner': 'Procurement', 'due': '2024-02-28'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Risk Treatment Plan'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _showAddTreatmentDialog(),
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _treatments.length,
        itemBuilder: (context, index) {
          final treatment = _treatments[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ExpansionTile(
              leading: CircleAvatar(
                backgroundColor: _getStatusColor(treatment['status']),
                child: Icon(_getStatusIcon(treatment['status']), color: Colors.white, size: 20),
              ),
              title: Text(treatment['risk'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Due: ${treatment['due']} • Owner: ${treatment['owner']}'),
              trailing: Container(
                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                decoration: BoxDecoration(
                  color: _getStatusColor(treatment['status']).withOpacity(0.2),
                  borderRadius: BorderRadius.circular(12),
                ),
                child: Text(
                  treatment['status'],
                  style: TextStyle(color: _getStatusColor(treatment['status']), fontSize: 12),
                ),
              ),
              children: [
                Padding(
                  padding: const EdgeInsets.all(16),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Treatment Plan:', style: TextStyle(fontWeight: FontWeight.bold)),
                      Text(treatment['treatment']),
                      const SizedBox(height: 12),
                      LinearProgressIndicator(
                        value: _getProgressValue(treatment['status']),
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
                              child: const Text('Update Status'),
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

  void _showAddTreatmentDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Add Treatment Plan'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'Data Breach', child: Text('Data Breach')),
                DropdownMenuItem(value: 'System Outage', child: Text('System Outage')),
                DropdownMenuItem(value: 'Compliance Failure', child: Text('Compliance Failure')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Select Risk'),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Treatment Plan'),
              maxLines: 3,
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Owner'),
            ),
            const SizedBox(height: 12),
            TextField(
              decoration: const InputDecoration(labelText: 'Due Date'),
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
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Add')),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'In Progress': return Colors.blue;
      case 'Planned': return Colors.orange;
      case 'Completed': return Colors.green;
      default: return Colors.grey;
    }
  }

  IconData _getStatusIcon(String status) {
    switch (status) {
      case 'In Progress': return Icons.play_circle;
      case 'Planned': return Icons.schedule;
      case 'Completed': return Icons.check_circle;
      default: return Icons.help;
    }
  }

  double _getProgressValue(String status) {
    switch (status) {
      case 'In Progress': return 0.5;
      case 'Planned': return 0.2;
      case 'Completed': return 1.0;
      default: return 0.0;
    }
  }
}