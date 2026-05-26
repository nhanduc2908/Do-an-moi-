import 'package:flutter/material.dart';

class ComplianceChecklistScreen extends StatefulWidget {
  const ComplianceChecklistScreen({super.key});

  @override
  State<ComplianceChecklistScreen> createState() => _ComplianceChecklistScreenState();
}

class _ComplianceChecklistScreenState extends State<ComplianceChecklistScreen> {
  final List<Map<String, dynamic>> _checklist = [
    {'control': 'AC-01', 'name': 'Implement access control policy', 'status': 'Completed', 'due': '2024-01-15'},
    {'control': 'AC-02', 'name': 'User access review', 'status': 'In Progress', 'due': '2024-01-20'},
    {'control': 'CR-01', 'name': 'Data encryption at rest', 'status': 'Completed', 'due': '2024-01-10'},
    {'control': 'CR-02', 'name': 'Data encryption in transit', 'status': 'Not Started', 'due': '2024-01-25'},
    {'control': 'NS-01', 'name': 'Firewall configuration review', 'status': 'In Progress', 'due': '2024-01-18'},
    {'control': 'IR-01', 'name': 'Incident response plan testing', 'status': 'Pending', 'due': '2024-01-30'},
  ];

  @override
  Widget build(BuildContext context) {
    final completedCount = _checklist.where((item) => item['status'] == 'Completed').length;
    final totalCount = _checklist.length;
    final progress = completedCount / totalCount;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Compliance Checklist'),
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
                    const Text('Overall Progress', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    LinearProgressIndicator(value: progress, backgroundColor: Colors.grey.shade200),
                    const SizedBox(height: 8),
                    Text('$completedCount of $totalCount controls completed', style: const TextStyle(color: Colors.grey)),
                  ],
                ),
              ),
            ),
          ),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _checklist.length,
              itemBuilder: (context, index) {
                final item = _checklist[index];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  child: CheckboxListTile(
                    value: item['status'] == 'Completed',
                    title: Text(item['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text('Control: ${item['control']} • Due: ${item['due']}'),
                    secondary: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: _getStatusColor(item['status']).withOpacity(0.2),
                        borderRadius: BorderRadius.circular(12),
                      ),
                      child: Text(
                        item['status'],
                        style: TextStyle(color: _getStatusColor(item['status']), fontSize: 12),
                      ),
                    ),
                    onChanged: (value) {
                      setState(() {
                        item['status'] = value == true ? 'Completed' : 'In Progress';
                      });
                    },
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
      case 'Completed': return Colors.green;
      case 'In Progress': return Colors.blue;
      case 'Pending': return Colors.orange;
      default: return Colors.grey;
    }
  }
}