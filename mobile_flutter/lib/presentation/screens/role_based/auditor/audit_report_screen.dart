import 'package:flutter/material.dart';

class AuditReportScreen extends StatefulWidget {
  const AuditReportScreen({super.key});

  @override
  State<AuditReportScreen> createState() => _AuditReportScreenState();
}

class _AuditReportScreenState extends State<AuditReportScreen> {
  final List<Map<String, dynamic>> _reports = [
    {'name': 'Q4 2024 Security Audit Report', 'date': '2024-01-15', 'size': '2.4 MB', 'type': 'PDF', 'status': 'Completed'},
    {'name': 'ISO 27001 Compliance Report', 'date': '2024-01-10', 'size': '1.8 MB', 'type': 'PDF', 'status': 'Completed'},
    {'name': 'GDPR Readiness Assessment', 'date': '2024-01-05', 'size': '3.1 MB', 'type': 'PDF', 'status': 'Draft'},
    {'name': 'Quarterly Access Review', 'date': '2023-12-28', 'size': '1.2 MB', 'type': 'Excel', 'status': 'Completed'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Audit Reports'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _showGenerateReportDialog(),
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: _reports.length,
        itemBuilder: (context, index) {
          final report = _reports[index];
          return Card(
            margin: const EdgeInsets.only(bottom: 12),
            child: ListTile(
              leading: Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(
                  color: _getTypeColor(report['type']).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Icon(_getTypeIcon(report['type']), color: _getTypeColor(report['type']), size: 28),
              ),
              title: Text(report['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${report['date']} • ${report['size']}'),
              trailing: Row(
                mainAxisSize: MainAxisSize.min,
                children: [
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: report['status'] == 'Completed' ? Colors.green.shade100 : Colors.orange.shade100,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(
                      report['status'],
                      style: TextStyle(color: report['status'] == 'Completed' ? Colors.green : Colors.orange, fontSize: 12),
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.download, color: Colors.blue),
                    onPressed: () {},
                  ),
                  IconButton(
                    icon: const Icon(Icons.share),
                    onPressed: () {},
                  ),
                ],
              ),
              onTap: () {},
            ),
          );
        },
      ),
    );
  }

  void _showGenerateReportDialog() {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Generate Audit Report'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'Security Audit', child: Text('Security Audit Report')),
                DropdownMenuItem(value: 'Compliance', child: Text('Compliance Report')),
                DropdownMenuItem(value: 'Access Review', child: Text('Access Review Report')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Report Type'),
            ),
            const SizedBox(height: 12),
            DropdownButtonFormField<String>(
              items: const [
                DropdownMenuItem(value: 'PDF', child: Text('PDF')),
                DropdownMenuItem(value: 'Excel', child: Text('Excel')),
                DropdownMenuItem(value: 'CSV', child: Text('CSV')),
              ],
              onChanged: (value) {},
              decoration: const InputDecoration(labelText: 'Format'),
            ),
            const SizedBox(height: 12),
          ],
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Cancel')),
          TextButton(onPressed: () => Navigator.pop(context), child: const Text('Generate')),
        ],
      ),
    );
  }

  Color _getTypeColor(String type) {
    switch (type) {
      case 'PDF': return Colors.red;
      case 'Excel': return Colors.green;
      default: return Colors.blue;
    }
  }

  IconData _getTypeIcon(String type) {
    switch (type) {
      case 'PDF': return Icons.picture_as_pdf;
      case 'Excel': return Icons.table_chart;
      default: return Icons.description;
    }
  }
}