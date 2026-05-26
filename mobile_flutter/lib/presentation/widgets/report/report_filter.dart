// Đường dẫn: mobile_flutter/lib/presentation/widgets/report/report_filter.dart

import 'package:flutter/material.dart';

class ReportFilter extends StatefulWidget {
  final Function(Map<String, String>) onFilterChanged;

  const ReportFilter({super.key, required this.onFilterChanged});

  @override
  State<ReportFilter> createState() => _ReportFilterState();
}

class _ReportFilterState extends State<ReportFilter> {
  String _selectedType = 'All';
  String _selectedFormat = 'All';
  DateTimeRange? _dateRange;

  final List<String> _types = ['All', 'Security Summary', 'Vulnerability Report', 'Compliance Report', 'Incident Report'];
  final List<String> _formats = ['All', 'PDF', 'Excel', 'CSV', 'JSON'];

  Future<void> _selectDateRange() async {
    final picked = await showDateRangePicker(
      context: context,
      firstDate: DateTime(2020),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() => _dateRange = picked);
      _applyFilter();
    }
  }

  void _applyFilter() {
    widget.onFilterChanged({
      'type': _selectedType == 'All' ? '' : _selectedType.toLowerCase().replaceAll(' ', '_'),
      'format': _selectedFormat == 'All' ? '' : _selectedFormat.toLowerCase(),
      'from': _dateRange?.start.toIso8601String() ?? '',
      'to': _dateRange?.end.toIso8601String() ?? '',
    });
  }

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.all(16),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            const Text('Filter Reports', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            _buildFilterRow('Report Type', _selectedType, _types, (value) {
              setState(() => _selectedType = value);
              _applyFilter();
            }),
            const SizedBox(height: 12),
            _buildFilterRow('Format', _selectedFormat, _formats, (value) {
              setState(() => _selectedFormat = value);
              _applyFilter();
            }),
            const SizedBox(height: 12),
            InkWell(
              onTap: _selectDateRange,
              child: Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 16),
                decoration: BoxDecoration(
                  border: Border.all(color: Colors.grey.shade300),
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      _dateRange != null
                          ? '${_dateRange!.start.toLocal().toString().split(' ')[0]} - ${_dateRange!.end.toLocal().toString().split(' ')[0]}'
                          : 'Select Date Range',
                    ),
                    const Icon(Icons.calendar_today),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildFilterRow(String label, String value, List<String> items, Function(String) onChanged) {
    return Row(
      children: [
        SizedBox(width: 100, child: Text(label)),
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
}