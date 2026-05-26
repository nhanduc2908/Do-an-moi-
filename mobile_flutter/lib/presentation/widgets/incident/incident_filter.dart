// Đường dẫn: mobile_flutter/lib/presentation/widgets/incident/incident_filter.dart

import 'package:flutter/material.dart';

class IncidentFilter extends StatefulWidget {
  final Function(Map<String, String>) onFilterChanged;

  const IncidentFilter({super.key, required this.onFilterChanged});

  @override
  State<IncidentFilter> createState() => _IncidentFilterState();
}

class _IncidentFilterState extends State<IncidentFilter> {
  String _selectedSeverity = 'All';
  String _selectedStatus = 'All';
  String _selectedCategory = 'All';

  final List<String> _severities = ['All', 'Critical', 'High', 'Medium', 'Low'];
  final List<String> _statuses = ['All', 'Open', 'Investigating', 'Resolved', 'Closed'];
  final List<String> _categories = ['All', 'Malware', 'Phishing', 'Unauthorized Access', 'Data Breach'];

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.all(16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text('Filter Incidents', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            _buildFilterRow('Severity', _selectedSeverity, _severities, (value) {
              setState(() => _selectedSeverity = value);
              _applyFilter();
            }),
            const SizedBox(height: 12),
            _buildFilterRow('Status', _selectedStatus, _statuses, (value) {
              setState(() => _selectedStatus = value);
              _applyFilter();
            }),
            const SizedBox(height: 12),
            _buildFilterRow('Category', _selectedCategory, _categories, (value) {
              setState(() => _selectedCategory = value);
              _applyFilter();
            }),
          ],
        ),
      ),
    );
  }

  Widget _buildFilterRow(String label, String value, List<String> items, Function(String) onChanged) {
    return Row(
      children: [
        SizedBox(width: 80, child: Text(label)),
        Expanded(
          child: DropdownButtonFormField<String>(
            value: value,
            items: items.map((item) => DropdownMenuItem(value: item, child: Text(item))).toList(),
            onChanged: (value) => onChanged(value!),
            decoration: const InputDecoration(
              border: OutlineInputBorder(),
              contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 8),
            ),
          ),
        ),
      ],
    );
  }

  void _applyFilter() {
    widget.onFilterChanged({
      'severity': _selectedSeverity == 'All' ? '' : _selectedSeverity.toLowerCase(),
      'status': _selectedStatus == 'All' ? '' : _selectedStatus.toLowerCase(),
      'category': _selectedCategory == 'All' ? '' : _selectedCategory.toLowerCase().replaceAll(' ', '_'),
    });
  }
}